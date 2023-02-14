<?php require('header.php'); ?>
<?php

global $wpdb;
$diq_forms = $wpdb->prefix . 'diq_forms';
$diq_form_shortcode= $wpdb->get_results("SELECT id, diq_form_label, diq_form_shortcode, diq_form_setting, diq_form_checkbox_label, diq_form_publish_date  FROM $diq_forms ORDER BY `diq_form_publish_date` DESC");

$counter = 0;
$url = get_admin_url();
$edit_page = menu_page_url('directiq_add_form', false);
// $add_new_url = get_site_url();
?>
<div class="row">
<div class="column1" style="width:75%;">
<div class="diq_form_list_main_wrap">
	<div class="diq_form_list_page_title"> 
		<div class="diq_form_list_title" style="display: flex; margin-top: 15px; padding-top: 26px;">
				<h1 style="font-size: 23px; font-weight: 600; margin: 0px;"> 
					<?php _e(ucwords("forms"), 'directiq'); ?> 
				</h1>
			<a href="<?php echo $url ?>admin.php?page=directiq_add_form" style="margin-left: auto;">
				<input type="button" class="button" name="" value="Add New">
			</a>
		</div>		
			<p>
               	<?php _e("Here are your subscription forms. Use the corresponding display this form inside a post, page or text widget.", "directiq"); ?>
        	</p>
        
		<?php
		if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * Class diq_list_Table
 */
class diq_list_Table extends WP_List_Table {

	/**
	 * Prepares the list of items for displaying.
	 */
	public function prepare_items() {

		$order_by = isset( $_GET['orderby'] ) ? $_GET['orderby'] : '';
		$order = isset( $_GET['order'] ) ? $_GET['order'] : '';
		$search_term = isset( $_POST['s'] ) ? $_POST['s'] : '';

		$this->items = $this->diq_list_table_data( $order_by, $order, $search_term );

		$diq_columns = $this->get_columns();
		$diq_hidden = $this->get_hidden_columns('ID');
		$ldul_sortable = $this->get_sortable_columns();

		$this->_column_headers = [ $diq_columns, $diq_hidden, $ldul_sortable ];
	}

	/**
	 * Wp list table bulk actions 
	 */
	public function get_bulk_actions() {

		return array(
			'diq_delete'	=> __( 'Delete')
		);

	}
	
	

	/**
	 * WP list table row actions
	 */
	public function handle_row_actions( $item, $column_name, $primary ) {

		if( $primary !== $column_name ) {
			return '';
		}
		$edit_page = menu_page_url('directiq_add_form', false);
		$action = [];
		$action['edit'] = '<a href="'.wp_nonce_url($edit_page.'&eid='.$item['ID'], 'edit-shortcode-form', '_wpnonce').'" class="diq_edit_sc_entry" target="_blank">'.__( 'Edit').'</a>';
		$action['delete'] = '<a href="javascript:void(0);" data-id='.$item['ID'].' class="diq_remove_sc_entry" style="color:#b32d2e;">'.__( 'Delete').'</a>';
		$action['quick-edit'] = '<button id="'.$item['ID'].'"class="diq-duplicate-button">'.__( 'Duplicate').'</button>';
		// $action['view'] = '<a style="display:none;">'.__( 'View').'</a>';

		return $this->row_actions( $action );
	}

	/**
	 * Display columns datas
	 */
	public function diq_list_table_data( $order_by = '', $order = '', $search_term = ''	 ) {

		?><section style="margin: 30px 0 0 0; ">
		<?php
		$data_array = [];

		$args = [
		    'post_type'      	=> 'post',
		    'post_status'    	=> 'publish',
		    'posts_per_page' 	=> -1,
		    'fields' 			=> 'ids'
		];
		global $wpdb;
		$diq_forms = $wpdb->prefix . 'diq_forms';
		$diq_form_shortcode= $wpdb->get_results("SELECT id, diq_form_label, diq_form_shortcode, diq_form_setting, diq_form_checkbox_label, diq_form_publish_date  FROM $diq_forms ORDER BY `diq_form_publish_date` DESC");
		$url = get_admin_url();
		$edit_page = menu_page_url('directiq_add_form', false);
		if(sizeof($diq_form_shortcode) <= 0){ ?>

		<?php }else{ ?>
		<?php
			foreach( $diq_form_shortcode as $value ) {
				
				$data_array[] = [
					
					'title' => '<a data-post-name="'.$value->diq_form_label.'" data-post-content="'.'$content'.'" data-post-id="'.$value->diq_form_label.'" href="'.wp_nonce_url($edit_page.'&eid='.$value->id, 'edit-shortcode-form', '_wpnonce').'" class="diq_edit_sc_entry" target="_blank""> '.$value->diq_form_label.' </a>',
					'diq_shortcode'				=> '<div id="diq_all_form_shortcode"><span class="diq-short-code">'.$value->diq_form_shortcode.'</span> <p id="diq_action_copy"><button class="diq-short-copy">Copy code</button><span id="copied" style="color: red;"></span></p>',
					'diq_publish_date'		=> '<div id="diq_all_form_linked_publish_date"><p>'.$value->diq_form_publish_date.'</p></div>',
					'diq_linked_list'			=> '<div id="diq_all_form_linked_list"> <p>'.$value->diq_form_checkbox_label.'</p> </div>',
					'ID' => $value->id,
			    ];

			}
		}

		?></section><?php
	    return $data_array;

	}

	/**
	 * Gets a list of all, hidden columns
	 */

	function get_hidden_columns( $screen ) {
	    if ( is_string( $screen ) ) {
	        $screen = convert_to_screen( $screen );
	    }
	 
	    $hidden = get_user_option( 'manage' . $screen->id . 'columnshidden' );
	 
	    $use_defaults = ! is_array( $hidden );
	 
	    if ( $use_defaults ) {
	        $hidden = array('ID');
	 
	        $hidden = apply_filters( 'default_hidden_columns', $hidden, $screen );
	    }
	    return apply_filters( 'hidden_columns', $hidden, $screen, $use_defaults );
	}

	/**
	 * Gets a list of columns.
	 */
	public function get_columns() {	

		$columns = array(
			'cb'			=> '<input class="diq_checkbox" type="checkbox"/>',
			'title'			=> __( 'Form Name'),
			'diq_shortcode'			=> __( 'Shortcode'),
			'diq_linked_list'		=> __( 'Linked List'),
			'diq_publish_date'	=> __( 'Last Update'),
			'ID'		=>  __( 'ID'),
		);
		return $columns;
	}

	/**
	 * Return column value
	 */
	public function column_default( $item, $column_name ) {

		switch ($column_name) {
			case 'title':
			case 'diq_shortcode':
			case 'diq_publish_date':
			case 'diq_linked_list':
			case 'diq_post_author':
			case 'ID':
			return $item[$column_name];
			default:
			return 'no list found';
		}
	}

	function column_cb($item) {
        return sprintf(
            '<a><input id="diq_check-'.$item['ID'].'" data-id='.$item['ID'].' class="diq_checkbox" type="checkbox"/></a>',
        );    
    }

    public function get_sortable_columns() {
    // 	$sortable_columns = array(
    // 		'title'  => array('title',false)
  		// );
  		// return $sortable_columns;
	}
	
}

$object = new diq_list_Table();
$object->prepare_items();
$object->display();
?>

</div>
</div>
</div>
<div class="column2" style="width:25%;">
	<div class="Sidebar-section">
      <?php include('sidebar.php'); ?>
    </div>
</div>
</div>
<?php

