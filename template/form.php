<?php require('header.php'); ?>
<?php require_once('admin.php'); ?>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30="crossorigin="anonymous"></script>
<?php
// $default_sc_msg = "complete and save this form to generate shortcode";
$default_sc_msg = ""; 
$hide_form_btn_default = 'checked="chekced"';
$edit_form = false;
if ( isset($_GET["eid"]) && isset($_GET['_wpnonce']) && wp_verify_nonce($_GET['_wpnonce'], 'edit-shortcode-form')) {
  $edit_form = true;
  global $wpdb;
  $diq_forms_table = $wpdb->prefix . 'diq_forms';

  $edit_id = intval($_GET["eid"]);
  $page_title = 'edit form';

  // Get shortcode form data for editing using edit_id
  $dq_edit_shortcode_form = $wpdb->get_row( $wpdb->prepare("SELECT * FROM {$diq_forms_table} WHERE `id` = '%s'",$edit_id),ARRAY_A );
  $diq_html_preview = $dq_edit_shortcode_form['diq_form_html'];

  $diq_form_messages = json_decode($dq_edit_shortcode_form['diq_form_message'] , true);
  $diq_success_msg = isset($diq_form_messages['success_msg']) ? $diq_form_messages['success_msg'] : "";
  $diq_email_msg = isset($diq_form_messages['email_msg']) ? $diq_form_messages['email_msg'] : "";
  $diq_required_field_msg = isset($diq_form_messages['required_field_msg']) ? $diq_form_messages['required_field_msg'] : "";
  $diq_general_error = isset($diq_form_messages['general_error_msg']) ? $diq_form_messages['general_error_msg'] : "";

  $diq_form_settings = json_decode($dq_edit_shortcode_form['diq_form_setting'] , true);

  if (intval($diq_form_settings['subscribe_list_checkbox']) === 0) {
    $subs_list_checkbox_val = $diq_form_settings['subscribe_list_checkbox'];
  }else{
    $subs_list_checkbox_val = explode(",", $diq_form_settings['subscribe_list_checkbox']);
  }
  // $subscribe_list_checkbox = ($diq_form_settings['subscribe_list_checkbox']) ? "checked = 'checked'" : "";

  // $existing_subs_yes = "";
  // $existing_subs_no = "";

  /*if (intval($diq_form_settings['update_existing_subs']) === 1 ) {
    // yes checked
    $existing_subs_yes = "checked='chekced'";
  }elseif(intval($diq_form_settings['update_existing_subs']) === 2){
    // no checked
    $existing_subs_no = "checked='chekced'";
  }else{
    // default checked
    $existing_subs_default = "checked='chekced'";
  }*/

  $hide_form_btn_yes = "";
  $hide_form_btn_no = "";
  if (intval($diq_form_settings['hide_form_btn']) === 1) {
    // yes checked
    $hide_form_btn_yes = "checked='chekced'";
  }elseif ($diq_form_settings['hide_form_btn'] === 2) {
    // no checked
    $hide_form_btn_no = "checked='chekced'";
  }else{
    // default checked
    $hide_form_btn_default = "checked='chekced'";
  }

  $redirect_url_front = isset($diq_form_settings['redirect_url_front']) ? $diq_form_settings['redirect_url_front'] : "";

  $form_appearance = $dq_edit_shortcode_form['diq_form_appearance'];
  $theme_appearance_color = get_background_color();
  $theme_color = "#".$theme_appearance_color;

  // $selected_option_1 = "";
  $selected_option_2 = "";
  $selected_option_3 = "";
  $selected_option_4 = "";
  $selected_option_5 = "";
  $selected_option_6 = "";
  $selected_option_7 = "";
  $selected_option_8 = "";

  /*if (intval($form_appearance) === 1) {
    // optino 1 selected
    $selected_option_1 = "selected";
  }*/
  if ($form_appearance === $theme_color) {
    // optino 2 selected
    $selected_option_2 = "selected";
  }elseif ($form_appearance === "#E6E4E4") {
    // optino 3 selected
    $selected_option_3 = "selected";
  }elseif($form_appearance === "#000000"){
    // optino 4 selected
    $selected_option_4 = "selected";
  }elseif($form_appearance === "#FFFFFF"){
    // optino 5 selected
    $selected_option_5 = "selected";
  }elseif($form_appearance === "#d9534f"){
    // optino 6 selected
    $selected_option_6 = "selected";
  }elseif($form_appearance === "#5cb85c"){
    // optino 7 selected
    $selected_option_7 = "selected";
  }elseif($form_appearance === "#428bca"){
    // optino 8 selected
    $selected_option_8 = "selected";
  }

}else{
  $page_title = 'add new form';
}

if (
  (
    isset($_GET["eid"]) &&
    !isset($_GET['_wpnonce'])
  ) ||
  (
    isset($_GET["eid"]) &&
    isset($_GET['_wpnonce']) && 
    !wp_verify_nonce($_GET['_wpnonce'], 'edit-shortcode-form')
  )
){ ?>
  <!-- redirect -->
  <div class="diq_timer_wrap">
    <span id="diq_redirect_timer">
      <?php _e(ucfirst('invalid request. redirecting in'),'directiq'); ?>&nbsp;:&nbsp;<span id="diq_redirect_time">5</span>      
    </span>
  </div>
  <?php $redirect_to_edit_page = menu_page_url('directiq_add_form', false); ?>
  <script type="text/javascript">
    var counter = 5;
    var test_redirect_var = "<?php echo $redirect_to_edit_page; ?>";
    var interval = setInterval(function() {
      counter--;
      if (counter <= 0) {
        clearInterval(interval);
        window.location.href = test_redirect_var;
        return;
      }else{
        $('#diq_redirect_time').text(counter);
      }
    }, 1000);
  </script>
  <!-- edit -->
<?php }else{ ?>
  <div class="row">
  <div class="column1" style="width:75%; border-right: 1px solid #ddd;">
  <div class="diq-add-form-top">
    <div class="middle-section" style="margin-top: 15px; padding-top: 15px;">
      <div class="titlediv"> 
        <div class="diq-add-form-title">
          <h1><?php _e(ucwords($page_title),'directiq'); ?></h1>
        </div>
      </div>
      <div data-info="<?php _e('form name input','directiq'); ?>" >
        <input type="text" id="diq_form_label" name="diq_form_label" placeholder="Enter the title of subscription form" value="<?php echo ($edit_form) ? $dq_edit_shortcode_form['diq_form_label'] : "" ?>">
      </div>
      <div class="content mt-10"> 
        <div class="diq_display_sc">
          <?php _e('Use the shortcode','directiq') ?> <input type='text' value='<?php 
          if ($edit_form) {
            echo $dq_edit_shortcode_form['diq_form_shortcode'];
            }
            ?>' readonly style="text-align: center;"> <?php _e('to display this form inside a post, page or text widget.','directiq')?>
          </div>
        </div>
        <div class="all-tab-section"> 
          <div class="tab" id="tabs">
            <button class="diq_add_form_tab_btn diq-is-active-tab" id="diq_add_form_tab_1"> 
              <?php _e("Fields", "directiq"); ?> 
            </button>
            <button class="diq_add_form_tab_btn" id="diq_add_form_tab_2"> 
              <?php _e("Messages", "directiq"); ?> 
            </button>
            <button class="diq_add_form_tab_btn" id="diq_add_form_tab_3"> 
              <?php _e("Settings", "directiq"); ?> 
            </button>
            <button class="diq_add_form_tab_btn" id="diq_add_form_tab_4"> 
              <?php _e("Appearance", "directiq"); ?> 
            </button>
          </div>
          <!-- submit modal -->
          <form id="<?php echo "form_submit_modal"; ?>" class="directiq_popup_modal needs-validation" novalidate="" action="#" method="post" data-alias="<?php echo "submit"; ?>" data-modal_id="<?php echo "diq_input_modal_submit_btn"; ?>">
          </form>
          <!-- submit modal -->
          <!-- agree modal -->
          <form id="<?php echo "form_agree_modal"; ?>" class="directiq_popup_modal needs-validation" novalidate="" action="#" method="post" data-alias="<?php echo "agree"; ?>" data-modal_id="<?php echo "diq_input_modal_agree_box"; ?>"></form>
          <!-- agree modal -->

          <!-- dynamic modal forms -->

          <?php          
          $add_static_modal = Array
          (

            '0' => array(
              'name' => 'submit',
              'shortCode' => 'submit',
              'valueType' => 'String',
              'isPublic' => '1',
              'totalCount' => '0',
              'dateFormat' => '',
            ),
            '1  ' => array(
              'name' => 'agree',
              'shortCode' => 'agree',
              'valueType' => 'String',
              'isPublic' => '1',
              'totalCount' => '0',
              'dateFormat' => '',
            )
          );

          if ($diq_form_dynamic_fields) {
            foreach ($diq_form_dynamic_fields as $arr_in => $field) { ?>
              <?php 
              $field_shortcode = $field['shortCode'];
              $modal_form_id = 'form_'.$field_shortcode.'_modal'; 
              $href_modal_id = "diq_input_modal_".$field_shortcode;
              ?>
              <form id="<?php echo $modal_form_id; ?>" class="directiq_popup_modal needs-validation" novalidate="" action="#" method="post" data-alias="<?php echo $field_shortcode; ?>"data-modal_id="<?php echo $href_modal_id; ?>">
              </form>
              <?php 
            }
          } ?>

          <!-- dynamic modal forms -->
          <?php 
          $edit_attribute = "";
          if ($edit_form) { 
            $edit_attribute = "data-diq_edit_form_id='".$edit_id."'";
          }

          ?>
          <form class="ajax" action="" method="post" id="diq_add_shortcode_form" <?php echo $edit_attribute; ?>>
            <!-- Tab 1 starts -->
            <div id="diq_add_form_tab_1" class="diq_add_form_tab_content diq-is-show-tab-content">
              <div id="master-section-available-fields">
                <div class="master-section-margin-s">
                  <h2>
                    <?php _e("Form Fields","directiq") ?>
                  </h2>
                  <p>
                    <?php _e("Click on ﬁelds below to add them into your form. (Scroll to the generated html code to change the layout.)","directiq") ?>
                  </p>
                </div>
              </div> 
              <div class="diq_modal_buttons_class">    
                <div class="main_directiq">
                  <div class="directiq_button">
                    <div class="diq_all_modal">

                      <?php /* Appearance grid starts*/ ?>
                      <div class="container">
                        <div class="row">
                          <div class="col diq_modal_label_name">
                            <?php _e(ucfirst('required'), 'directiq'); ?>
                          </div>
                        </div>
                        <!-- Required fields starts -->
                        <div class="row">
                          <div class="col">
                            <div class="btn-group" style="margin-bottom: 10px;">
                              <?php 

                              if ($diq_form_dynamic_fields) {
                                  foreach ($diq_form_dynamic_fields as $arr_in => $field) {
                                      $field_name = $field['name'];
                                      $field_shortcode = $field['shortCode'];
                                      $field_data_type = trim(strtolower($field['valueType']));
                                      if ( $field_data_type === "string") {
                                          $field_input_type = "text";
                                      }elseif ($field_data_type === "number") {
                                          $field_input_type = "number";
                                      }elseif ($field_data_type === "date") {
                                          $field_input_type = "date";
                                      }else{
                                          $field_input_type = "text";
                                      }

                                      if ($field_shortcode === "email") {
                                          $field_input_type = "email";
                                      }

                                      $href_btn_id = "#diq_input_modal_".$field_shortcode;
                                      $href_modal_id = "diq_input_modal_".$field_shortcode;
                                      $modal_close_btn = $field_shortcode.'close';
                                      $modal_form_id = 'form_'.$field_shortcode.'_modal';
                                      $modal_input_field_label = $field_shortcode.'_label';
                                      $modal_input_field_id = $field_shortcode.'_id';
                                      $modal_input_field_class = $field_shortcode.'_class';
                                      $modal_input_field_type = $field_shortcode.'_type';
                                      $modal_input_field_placeholder = $field_shortcode.'_placeholder'; ?>
                                      
                              <?php if ($field_shortcode === "email") {
                                         include('fields.php'); 
                                      }?>
                                  <?php }
                                }        
                                ?>
                            </div>                            
                          </div>
                        </div>
                        <!-- Required fields ends -->
                        <div class="row">
                          <div class="col-8 diq_modal_label_name">
                            <?php _e(ucfirst('standard fields'), 'directiq'); ?>
                          </div>
                          <div class="col-4 diq_modal_label_name">
                            <?php _e(ucfirst('other'), 'directiq'); ?>
                          </div>
                        </div>
                        <div class="row">
                          <!-- Stndard fields starts-->
                          <div class="col-8">
                        <?php
                            if ($diq_form_dynamic_fields) {
                                  foreach ($diq_form_dynamic_fields as $arr_in => $field) {
                                      $field_name = $field['name'];
                                      $field_shortcode = $field['shortCode'];
                                      $field_data_type = trim(strtolower($field['valueType']));
                                      if ( $field_data_type === "string") {
                                          $field_input_type = "text";
                                      }elseif ($field_data_type === "number") {
                                          $field_input_type = "number";
                                      }elseif ($field_data_type === "date") {
                                          $field_input_type = "date";
                                      }else{
                                          $field_input_type = "text";
                                      }

                                      if ($field_shortcode === "email") {
                                          $field_input_type = "email";
                                      }

                                      $href_btn_id = "#diq_input_modal_".$field_shortcode;
                                      $href_modal_id = "diq_input_modal_".$field_shortcode;
                                      $modal_close_btn = $field_shortcode.'close';
                                      $modal_form_id = 'form_'.$field_shortcode.'_modal';
                                      $modal_input_field_label = $field_shortcode.'_label';
                                      $modal_input_field_id = $field_shortcode.'_id';
                                      $modal_input_field_class = $field_shortcode.'_class';
                                      $modal_input_field_type = $field_shortcode.'_type';
                                      $modal_input_field_placeholder = $field_shortcode.'_placeholder'; ?>
                                 <div class="btn-group" style="margin-bottom: 10px;">     
                              <?php if ($field_shortcode !== "email") {
                                         include('fields.php'); 
                                      }?>
                                  </div>
                                  <?php }
                                }        
                                ?>
                          </div>
                          <!-- Stndard fields ends-->
                          <!-- Other fields starts-->

                          <div class="col-4">
                            <?php
                            if ($add_static_modal) {
                                  foreach ($add_static_modal as $arr_in => $field) {
                                      $field_name = $field['name'];
                                      $field_shortcode = $field['shortCode'];
                                      $field_data_type = trim(strtolower($field['valueType']));
                                      ?>
                                  <div class="btn-group" style="margin-bottom: 10px;">     
                                    <?php 
                                      include('static_fields.php'); 
                                    ?>
                                  </div>
                                  <?php }
                                } ?>
                            
                          </div>
                          <!-- Other fields ends-->
                        </div>
                        <div class="row">
                          <div class="col diq_modal_label_name">
                            <?php _e(ucfirst('Custom Fields'), 'directiq'); ?>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col">
                            <p>
                              Wanna create custom ﬁelds? <a href="https://app.directiq.com/extras" target="_blank"> Go to your DirectIQ panel.</a>
                            </p>
                          </div>
                        </div>
                      </div>
                      <?php /* Appearance grid ends*/ ?>
                    </div>
                  </div> 
                </div>
                <?php /*dynamic buttons ends*/ ?>
              </div>
              <div style="margin-top: 30px" data-info="<?php _e('codemirror textarea','directiq'); ?>" class="master-section-form-markup-wrap" >
                <div class="textarea-row">
                  <div class="column">
                    <h4 style="font-weight: 600;">
                      <?php _e("Form Code (HTML)", "directiq"); ?>
                    </h4>
                    <p style="ma">
                      <?php _e("Change the sorting of elements and layout here.", "directiq"); ?>
                    </p>
                    <textarea data-test_info_textarea="test449" style="width:100%" cols="160" rows="20" name="forms" id="forms" value=""><?php if ($edit_form) { echo $diq_html_preview; }?></textarea>
                  </div>
                </div>
                <div>
                  <h4 style="font-weight: 600;">
                    <?php _e("Form Preview", "directiq"); ?>
                  </h4>
                  <p>
                    <?php _e("Use the Appearance tab to ﬁnalize your style."); ?>
                  </p>
                    <iframe id="preview"></iframe>

                </div>
              </div>
            <!-- <div data-info="<?php _e('form name input','directiq'); ?>" >
              <span><?php _e(ucwords('form name / label'), 'directiq') ?> : </span>
              <input type="text" id="diq_form_label" name="diq_form_label" placeholder="Type name for this form" value="<?php echo ($edit_form) ? $dq_edit_shortcode_form['diq_form_label'] : "" ?>">
            </div> -->
            <div data-info="<?php _e('save button for add new form page','directiq'); ?>">
              <input type="submit" class="button button-primary" id="diq_save_sc_form" name="diq_save_sc_form" value="Save Changes" form="diq_add_shortcode_form"/>
            </div>
            <div class="content mt-10">     
              <div class="diq_display_sc">
                <?php _e('Use the shortcode','directiq') ?> <input type='text' value='<?php 
                if ($edit_form) {
                  echo $dq_edit_shortcode_form['diq_form_shortcode'];
                  }
                  ?>' readonly style="text-align: center;"> <?php _e('to display this form inside a post, page or text widget.','directiq')?>
                <!-- <code>
                  <?php echo ucwords($default_sc_msg); ?>
                </code> -->
              </div>
            </div>
          </div>
          <!-- Tab 1 ends -->

          <!-- Tab 2 starts -->
          <div id="diq_add_form_tab_2" class="diq_add_form_tab_content">
            <h2>  
              <?php _e("Form Messages", "directiq"); ?> 
            </h2>
            <p>
              <?php _e("Here you can customize or translate from messages ","directiq") ?>  HTML tags like <b>&lt;strong&gt;&lt;em&gt;&lt;a&gt;</b> are allowed in the message
            </p>
            <table class="form-table master-section-form-messages">
              <tbody>
                <tr valign="top">
                  <td scope="row"> 
                    <div class="diq_row_msg_label">
                      <label for="diq_scfrm_text_success"> 
                        <?php _e(ucwords("success(Normal)"), "directiq"); ?> 
                      </label> 
                    </div>
                  </td>
                  <td>
                    <input type="text" name="diq_scfrm_text_success" class="widefat" id="diq_scfrm_text_success" value="<?php echo ($edit_form) ? $diq_success_msg : "Thank you, your form have been successfully submitted."; ?>" placeholder="<?php _e('Type message...', 'directiq'); ?>" />
                    <p class="description">
                      <?php _e("The text that shows when an email address is successfully submitted to the selected list(s).", "directiq"); ?>
                    </p>
                  </td>
                </tr>
                <!-- <tr valign="top">
                  <td scope="row">
                    <div class="diq_row_msg_label">
                      <label for="diq_scfrm_text_success"> 
                        <?php _e(ucwords("success(Double opt-in)"), "directiq"); ?> 
                      </label> 
                    </div>
                  </td>
                  <td>
                    <input type="text" name="diq_scfrm_text_double" class="widefat" id="diq_scfrm_text_double" value="" placeholder="<?php _e('Type message...', 'directiq'); ?>" />
                    <p class="description">
                      <?php _e("The text that shows when an email address is successfully submitted but needs email confirmation.", "directiq"); ?>
                    </p>
                  </td>   
                </tr> -->
                <tr valign="top">
                  <td scope="row">
                    <div class="diq_row_msg_label">
                      <label for="diq_scfrm_text_email">  
                        <?php _e(ucwords("Invalid email address"), "directiq"); ?> 
                      </label>
                    </div> 
                  </td>
                  <td>
                    <input type="text" class="widefat" id="diq_scfrm_text_email" name="diq_scfrm_text_email" value="<?php echo ($edit_form) ? $diq_email_msg : "Please provide a valid email address."; ?>" placeholder="<?php _e('Type message...', 'directiq'); ?>">
                    <p class="description">
                      <?php _e(ucwords("The text that shows when an invalid email address is given."), "directiq"); ?> 
                    </p>
                  </td>
                </tr>
                <tr valign="top">
                  <td scope="row"> 
                    <div class="diq_row_msg_label">
                      <label for="diq_scfrm_text_required_field"> 
                        <?php _e(ucwords('required field missing'), 'directiq'); ?>  
                      </label>
                    </div>
                  </td>
                  <td>
                    <input type="text" class="widefat" id="diq_scfrm_text_required_field" name="diq_scfrm_text_required_field" value="<?php echo ($edit_form) ? $diq_required_field_msg : "Please fill in the required fields."; ?>" placeholder="<?php _e('Type message...', 'directiq'); ?>">
                    <p class="description">  
                      <?php _e(ucwords("The text that shows when a required field for the selected list(s) is missing."), "directiq"); ?> 
                    </p>
                  </td>
                </tr>
                <!-- <tr valign="top">
                  <td scope="row"> 
                    <div class="diq_row_msg_label">
                      <label for="diq_scfrm_text_success"> 
                        <?php //_e(ucwords("Already subscribed"), "directiq"); ?> 
                      </label>
                    </div> 
                  </td>
                  <td>
                    <input type="text" name="diq_scfrm_text_double" class="widefat" id="diq_scfrm_text_double" value="" placeholder="<?php _e('Type message...', 'directiq'); ?>" />
                    <p class="description">
                      <?php //_e("The text that shows when the given email is already subscribed to the selected list(s).", "directiq"); ?>
                    </p>
                  </td>
                </tr> -->
                <tr valign="top">
                  <td scope="row"> 
                    <div class="diq_row_msg_label">
                      <label for="diq_scfrm_text_success"> 
                        <?php _e(ucwords("General error"), "directiq"); ?> 
                      </label>
                    </div> 
                  </td>
                  <td>
                    <input type="text" name="diq_scfrm_general_error" class="widefat" id="diq_scfrm_general_error" value="<?php echo ($edit_form) ? $diq_general_error : "Oops. Something went wrong. Please try again later."; ?>" placeholder="<?php _e('Type message...', 'directiq'); ?>" />
                    <p class="description">
                      <?php _e("The text that shows when a general error occured).", "directiq"); ?>
                    </p>
                  </td>
                </tr>
          <!-- <tr valign="top">
                  <td colspan="2">
                    <p class="description">
                      HTML tags like <code>&lt;strong&gt;&lt;em&gt;&lt;a&gt;</code> are allowed in the message fields. 
                    </p>
                  </td>
                </tr> -->
              </tbody> 
            </table>
            <div>
              <input type="submit" class="button button-primary" id="diq_save_sc_form" name="diq_save_sc_form" value="Save Changes" form="diq_add_shortcode_form"/>
            </div>
            <div class="diq_display_sc">
             <?php _e('Use the shortcode','directiq') ?> <input type='text' value='<?php 
              if ($edit_form) {
              echo $dq_edit_shortcode_form['diq_form_shortcode'];
              } ?>' readonly style="text-align: center;"> <?php _e('to display this form inside a post, page or text widget.','directiq')?>
              </div>
            </div>
            <!-- Tab 2 ends -->
            <!-- Tab 3 starts -->
            <div id="diq_add_form_tab_3" class="diq_add_form_tab_content">
              <h2>
                <?php _e(ucwords('form settings'), 'directiq') ?> 
              </h2>
              <table class="form-table" style="table-layout: fixed;">
                <tbody>
                  <tr valign="top">
                    <th scope="row">
                      <?php _e(ucwords('lists connected to this form'), 'directiq') ?>  
                    </th>
                    <td class="nowrap">
                      <div>
                        <?php if ($diq_form_subscription_lists) {
                          foreach ($diq_form_subscription_lists as $subs_index => $subs_val) { 
                          // $element_id = "diq_subscribe_list_checkbox_".$subs_val['id'];
                            $element_id = $subs_val['name'];
                            $opt_checked = "";
                            if ($edit_form && $subs_list_checkbox_val != 0) {
                              if ( in_array($subs_val['id'], $subs_list_checkbox_val)) {
                                $opt_checked = 'checked="checked"';
                              }
                            } 
                            ?>
                            <input type="checkbox" name="diq_subscribe_list_checkbox[]" class="diq_subs_list_checkbox" value="<?php echo $subs_val['id']; ?>" id="<?php echo $element_id; ?>" <?php echo ($edit_form) ? $opt_checked : ""; ?>>
                            <label for="<?php echo $element_id; ?>" id="diq_sttg_label" class="<?php echo $subs_val['name']?>">
                              <?php _e($subs_val['name'] , 'directiq'); ?>
                            </label>
                            <?php 
                          // } 
                        } ?>
                      </div>
                      <p class="description">
                        <?php _e(ucfirst("Select the list(s) to which people who submit this form should be subscribed."), "directiq"); ?> 
                      </p>
                    </td>            
                  </tr>
                        <?php 
                          // } 
                        } else {
                           _e(ucfirst("<span style='color:red; font-weight:bold;'> No contact list! </span> 
                            <a href='https://app.directiq.com/' target='_blank'>Please login to your DirectIQ account</a> and create at least one contact list When you're done, comeback here and refersh the page."), "directiq"); 
                        }?>
                    <!-- <tr valign="top">
                            <th scope="row">
                              <?php _e(ucwords("Use double opt-in?"), "directiq"); ?>
                            </th>
                            <td class="nowrap">
                                <label for="oubleoptin_yes" id="diq_rd_title">
                                  &rlm;Yes
                                </label>
                                <input type="radio" id="doubleoptin_yes" name="doubleoptin" value="1" <?php //checked( '1', get_option( 'doubleoptin' ) );?> checked="checked">
                                 &nbsp;
                                <label for="doubleoptin_no" id="diq_rd_title">
                                  &rlm; No
                                </label>
                                <input type="radio" id="doubleoptin_no" name="doubleoptin" value="2" >
                                
                                <p class="description">
                                  <?php _e(ucfirst("When double opt-in is selected, the user will have to pass an email confirmation to subscribe."), "directiq");?></p>
                            </td>
                          </tr> -->
              <!-- <tr valign="top">
                      <th scope="row"> 
                        <?php _e(ucwords("Update existing subscribers?"), "directiq"); ?> 
                      </th>
                      <td class="nowrap">
                        <label for="diq_update_existing_subs_radio_yes" id="diq_rd_title">
                          &rlm;Yes
                        </label> 
                        <input type="radio" id="diq_update_existing_subs_radio_yes" name="diq_update_existing_subs_radio_btn" value="1" checked="checked"<?php //echo ($edit_form) ? $existing_subs_yes : $existing_subs_default; ?>>
                        &nbsp;
                        <label for="diq_update_existing_subs_radio_no" id="diq_rd_title">
                          &rlm; No
                        </label>
                        <input type="radio" id="diq_update_existing_subs_radio_no" name="diq_update_existing_subs_radio_btn" value="2" <?php //echo ($edit_form) ? $existing_subs_no : ""; ?>>
                        <p class="description">
                          <?php _e(ucfirst("Select \"yes\" if you want to update existing subscribers with the data that is sent."), "directiq"); ?> 
                           <?php _e(ucfirst("Select \"no\" if you want to show \"already subscribered\" error."), "directiq"); ?> 
                        </p>
                      </td> 
                    </tr> -->
                  </tbody> 
                </table>
                <h3>
                  <?php _e(ucwords('form behaviour'), 'directiq') ?>
                </h3>
                <table class="form-table" style="table-layout: fixed;">
                  <tbody>
                    <tr valign="top">
                      <th scope="row"> 
                        <?php _e(ucfirst("Hide form after a successful sign-up?"), "directiq"); ?> 
                      </th>
                      <td class="nowrap">
                        <input type="radio" id="diq_hide_form_radio_yes" name="diq_hide_form_radio_btn" value="1" <?php echo ($edit_form) ? $hide_form_btn_yes : $hide_form_btn_default; ?>>
                        <label for="diq_hide_form_radio_yes" id="diq_rd_title">
                          &rlm;YES 
                        </label> 
                        &nbsp;
                        <input type="radio" id="diq_hide_form_radio_no" name="diq_hide_form_radio_btn" value="2" <?php echo ($edit_form) ? $hide_form_btn_no : ""; ?>>
                        <label for="diq_hide_form_radio_no" id="diq_rd_title">
                          &rlm;NO
                        </label>
                        <p class="description">
                          <?php _e("Select \"yes\" to hide the form fields after a successful sign-up.", "directiq"); ?>
                        </p>
                      </td>
                    </tr>
                    <tr valign="top">
                      <th scope="row"> 
                        <label for="diq_redirect_url_front" id="diq_rd_title"> 
                          <?php _e("Redirect to URL after successful sign-ups", "directiq"); ?>
                        </label> 
                      </th>
                      <td>
                        <input type="url" class="widefat" id="diq_redirect_url_front" name="diq_redirect_url_front" 
                        value="<?php echo ($edit_form) ? $redirect_url_front : ""; ?>">
                        <p class="description">
                          Enter URL (including https://) to redirect. Leave empty for no redirect.
                        </p>
                        <p class="description">
                          <?php _e(ucfirst("Your \"subscribed\" message will not show when redirecting to another page, so make sure to let your visitors know they were successfully subscribed."), 'directiq') ?>
                        </p>
                      </td>
                    </tr>
                  </tbody> 
                </table>
                <div>
                  <input type="submit" class="button button-primary" id="diq_save_sc_form" name="diq_save_sc_form" value="Save Changes" form="diq_add_shortcode_form"/>
                </div>
                <div class="diq_display_sc">
                  <?php _e('Use the shortcode','directiq') ?> <input type='text' value='<?php 
              if ($edit_form) {
                echo $dq_edit_shortcode_form['diq_form_shortcode'];
              }
              ?>'readonly style="text-align: center;"> <?php _e('to display this form inside a post, page or text widget.','directiq')?>
                  </div>
                </div>
                <!-- Tab 3 ends-->
                <!-- Tab 4 -->
                <div id="diq_add_form_tab_4" class="diq_add_form_tab_content">
                  <h2>
                    <?php _e(ucwords("Form Appearance"),"directiq"); ?>
                  </h2>
                  <table class="form-table">
                    <tbody> 
                      <tr valign="top">
                        <th scope="row"> 
                          <label for="diq_sc_form_appearance" style="display: inline;">
                            <?php _e(ucfirst("Form Style"), "directiq"); ?> 
                          </label> 
                        </th>
                        <?php 
                        $diq_current_theme_bgc = get_background_color();
                        ?>
                        <td class="nowrap valigntop">
                          <select name="diq_sc_form_appearance" id="diq_sc_form_appearance">
                          <!-- 
                          <option value="1" <?php //echo ($edit_form) ? $selected_option_1 :"selected"; ?>>
                            <?php //_e(ucwords("select form style color"), "directiq"); ?>
                          </option>
                        -->
                        <option value='<?php echo "#".$diq_current_theme_bgc ?>' <?php echo ($edit_form) ? $selected_option_2 : "selected"; ?>>
                          <?php //echo wp_get_theme();?>
                          <?php _e(ucwords("Default style (form theme)"),"directiq") ?>
                        </option>
                        <option value='#E6E4E4' <?php echo ($edit_form) ? $selected_option_3 : ""; ?>>
                          <?php _e(ucwords("light theme"),"directiq") ?>
                        </option>
                        <option value='#000000' <?php echo ($edit_form) ? $selected_option_4 : ""; ?>>
                          <?php _e(ucwords("dark theme"),"directiq") ?> 
                        </option>
                        <option value='#FFFFFF' <?php echo ($edit_form) ? $selected_option_5 : ""; ?>> 
                          <?php _e(ucwords("white theme"),"directiq") ?> 
                        </option>
                        <option value='#d9534f' <?php echo ($edit_form) ? $selected_option_6 : ""; ?>> 
                          <?php _e(ucwords("red theme"),"directiq") ?> 
                        </option>
                        <option value='#5cb85c' <?php echo ($edit_form) ? $selected_option_7 : ""; ?>> 
                          <?php _e(ucwords("green theme"),"directiq") ?> 
                        </option>
                        <option value='#428bca' <?php echo ($edit_form) ? $selected_option_8 : ""; ?>>
                          <?php _e(ucwords("blue theme"),"directiq") ?> 
                        </option>
                      </select>
                      <p class="description"> 
                        <?php _e("If you leave this as \"default\" then form will use your wordpress theme's default styling. Alternatively you can use our presets or use custom CSS styling below.", "directiq"); ?>
                      </p>
                    </td>
                  </tr>
                </tbody> 
              </table>
              
              <div>
                <label for="diq_custom_css_appearance">
                  <?php _e(ucfirst("Custom CSS"), "directiq"); ?> 
                </label> 
                <p class="description"> 
                  <?php _e("you can use your own CSS below. The code will override whatever selection you set above as form style.", "directiq"); ?>
                </p>
                <div class="custom">
                  <textarea id="code" class="code" name=""  cols="40" rows="5"><?php if($edit_form){ echo $dq_edit_shortcode_form['diq_form_custom_css'];}?></textarea>
                </div>
              </div>
              <div>
                <input type="submit" class="button button-primary" id="diq_save_sc_form" name="diq_save_sc_form" value="Save Changes" form="diq_add_shortcode_form"/>
              </div>
              <div class="diq_display_sc">
                <?php _e('Use the shortcode','directiq') ?> <input type='text' value='<?php 
            if ($edit_form) {
            echo $dq_edit_shortcode_form['diq_form_shortcode'];
            }
            ?>' readonly style="text-align: center;"> <?php _e('to display this form inside a post, page or text widget.','directiq')?>autorefresh.js
                </div>
              </div>

              <!-- Tab 4 ends -->
            </form>
          </div>
        </div>
      </div>
    </div>
      <div class="column2" style="width: 25%;">
        <div class="Sidebar-section">
          <?php include('sidebar.php'); ?>
        </div>
      </div>
      <?php } ?>