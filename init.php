<?php 
/**
 * Initialization of class directiq
 * */
if (!class_exists('InitializeDirectIq')) {
    class InitializeDirectIq{

        private $diq_screen;
        private static $instance;

        static function GetInstance()
        {

            if (!isset(self::$instance))
            {
                self::$instance = new self();
            }
            return self::$instance;
        }
        // $logo = include DIRECTIQ_PLUGIN_DIR_PATH.'';
        public function diq_add_menu_pages(){
             $this->diq_screen =  add_menu_page(
                                    __('DirectIQ', 'directiq'), #page_title
                                    __('DirectIQ', 'directiq'), #menu_title 
                                    'manage_options',
                                    'directiq',
                                    array($this,'diq_connection_form'),
                                    DIRECTIQ_PLUGIN_DIR_URL."images/logo.png", #icon_url
                                    56
                                );   
  
            add_submenu_page( 
                'directiq',
                __('DirectIQ', 'directiq'), #page_title
                __('General Setup', 'directiq'), #menu_title
                'manage_options', #capability
                'directiq',  #menu_slug
                array($this,'diq_connection_form') #callable $function
            );

            $api_key = get_option( 'diq_rest_key' );
            $url = "https://help.directiq.com/en/collections/3289205-apps-and-plugins#directiq-wordpress-plug-in";

            if ($api_key) {
                add_submenu_page( 
                'directiq', #parent_slug
                __('directIQ', 'directiq'), #page_title
                __('Add New Form', 'directiq'), #menu_title
                'manage_categories', #capability
                'directiq_add_form', #menu_slug
                array($this, 'diq_add_new_form') #callable $function
            );

                add_submenu_page(
                'directiq', #parent_slug
                __('directIQ', 'directiq'), #page_title
                __('Forms', 'directiq'), #menu_title
                'manage_categories', #capability
                'directiq_forms', #menu_slug
                array($this,'diq_form_list') #callable $function
            );
            }

            add_submenu_page( 
                'directiq', #parent_slug
                __('directIQ', 'Help/FAQ'), #page_title
                __('<b class="target_blank">Help / FAQ</b>', 'directiq'), #menu_title
                'manage_categories', #capability
                $url, #menu_slug
                ''
                // array($this, 'diq_faq') #callable $function
            );

        }

        /**
         * Include API connection form
         * */
        public static function diq_connection_form() {
            // get current time
            $diq_current_check_time = time();
            $diq_api_key = get_option( 'diq_rest_key' );
              // get next check time for dynamic form custom fields
            $diq_recheck_authorize = get_option('diq_recheck_authorize', null);

            if (is_null($diq_recheck_authorize) || empty($diq_recheck_authorize)) {
                // perform recheck and field updates
                self::diq_refresh_form_authorize();
            }elseif(!is_null($diq_recheck_authorize) && $diq_current_check_time >= $diq_recheck_authorize) {
                // perform recheck and field updates
                self::diq_refresh_form_authorize();
            }
            include DIRECTIQ_PLUGIN_DIR_PATH.'/template/connection.php';
        }  
        
        

        /**
         * Add new form  
         * */
        public static function diq_add_new_form() {
            // get current time
            $diq_current_check_time = time();

            // get next check time for dynamic form custom fields
            $diq_recheck_fields = get_option('diq_recheck_fields', null);

            if (is_null($diq_recheck_fields) || empty($diq_recheck_fields)) {
                // perform recheck and field updates
                self::diq_refresh_form_fields();
            }elseif(!is_null($diq_recheck_fields) && $diq_current_check_time >= $diq_recheck_fields) {
                // perform recheck and field updates
                self::diq_refresh_form_fields();
            }

            // get next check time for subscribe lists for settings
            $diq_recheck_subscription_lists = get_option('diq_recheck_subscription_lists', null);

            if (is_null($diq_recheck_subscription_lists) || empty($diq_recheck_subscription_lists)) {
                // perform recheck and lists updates
                self::diq_refresh_form_subscribe_lists();
            }elseif(!is_null($diq_recheck_subscription_lists) && $diq_current_check_time >= $diq_recheck_subscription_lists) {
                // perform recheck and lists updates
                self::diq_refresh_form_subscribe_lists();
            }

            $diq_form_dynamic_fields = json_decode(get_option('diq_add_form_fields'),true);

            $diq_form_subscription_lists = json_decode(get_option('diq_add_subscription_lists'),true);
            
            include DIRECTIQ_PLUGIN_DIR_PATH.'/template/form.php';
        }

        /**
         * Display saved forms 
         * */
        public static function diq_form_list() {
            include DIRECTIQ_PLUGIN_DIR_PATH.'/template/list.php';
        }
        
        /**
         * Help and FAQ
         * */
         public static function diq_faq() {
            include "https://help.directiq.com/en/collections/3289205-apps-and-plugins#directiq-wordpress-plug-in";
        }

        public static function diq_refresh_form_authorize(){
            // get current time
            $diq_current_check_time = time();

            // call and get all fields
            $diq_api_key = get_option('diq_rest_key');
            $rest_base_url = trailingslashit(esc_url_raw(DIRECTIQ_REST_BASE_URL));

            // Fetch fields from api 
            $rest_url = $rest_base_url.'authorize';
            $authorize_args = array(
                "headers" => [
                    "x-api-key" => $diq_api_key,
                    "Content-Type" => "application/json, charset=utf-8"
                ]
            );
            $diq_fetch_authorize_call = wp_remote_get( $rest_url, $authorize_args );

            $diq_fetch_authorize_body = wp_remote_retrieve_body($diq_fetch_authorize_call);

            $diq_fetch_authorize_response_code = wp_remote_retrieve_response_code($diq_fetch_authorize_call);
    
            // call and get all fields
            if ($diq_fetch_authorize_response_code === 200) {
                // success
                // set fields
                // update_option('diq_rest_key', $diq_fetch_authorize_body);
                $next_check = $diq_current_check_time + (60*30);
                // set recheck time
                update_option('diq_recheck_authorize', $next_check);
            }else{
                // fail
                // set fields to null
                update_option('diq_rest_key', null);

                // set recheck time to null
                update_option('diq_recheck_authorize', null);
            }
        }


        /**
         * Get fields list from api for generating dynamic fields for 
         * modal form
         * */
        public static function diq_refresh_form_fields(){
            // get current time
            $diq_current_check_time = time();

            // call and get all fields
            $diq_api_key = get_option('diq_rest_key');
            $rest_base_url = trailingslashit(esc_url_raw(DIRECTIQ_REST_BASE_URL));

            // Fetch fields from api 
            $rest_url = $rest_base_url.'fields';
            $fields_args = array(
                "headers" => [
                    "x-api-key" => $diq_api_key,
                    "Content-Type" => "application/json, charset=utf-8"
                ]
            );
            $diq_fetch_fields_call = wp_remote_get( $rest_url, $fields_args );

            $diq_fetch_fields_body = wp_remote_retrieve_body($diq_fetch_fields_call);

            $diq_fetch_field_response_code = wp_remote_retrieve_response_code($diq_fetch_fields_call);
            // call and get all fields
            if(isset($_GET['page']) && trim($_GET['page']) == "directiq_add_form") {
                if ($diq_fetch_field_response_code === 200) {
                    // success
                    // set fields
                    update_option('diq_add_form_fields', $diq_fetch_fields_body);
                }else{
                    // fail
                    // set fields to null
                    update_option('diq_add_form_fields', null);
                }
            }
        }

        /**
         * Get subscribe list from api for assigning dynamic fields for 
         * modal form
         * */
        public static function diq_refresh_form_subscribe_lists(){
            // get current time
            $diq_current_check_time = time();

            // call and get all fields
            $diq_api_key = get_option('diq_rest_key');
            $rest_base_url = trailingslashit(esc_url_raw(DIRECTIQ_REST_BASE_URL));

            // Fetch lists from api
            $subscription_rest_url = $rest_base_url.'lists';
            $subscription_lists_args = array(
                "headers" => [
                    "x-api-key" => $diq_api_key,
                    "Content-Type" => "application/json, charset=utf-8"
                ]
            );
            $diq_form_subscription_list_call = wp_remote_get( $subscription_rest_url, $subscription_lists_args );

            $diq_form_subscription_list_body = wp_remote_retrieve_body($diq_form_subscription_list_call);

            $diq_form_subscription_list_response = wp_remote_retrieve_response_code($diq_form_subscription_list_call);

           
            // call and get all fields0
    
            if(isset($_GET['page']) && trim($_GET['page']) == "directiq_add_form") {
                if ($diq_form_subscription_list_response === 200) {
                    // success
                    // set fields
                    update_option('diq_add_subscription_lists', $diq_form_subscription_list_body);
                }
                else{
                    // fail
                    // set fields to null
                    update_option('diq_add_subscription_lists', null);
                }
            }
        } 

        /**
         * Add options to database with inital values
         * */
        public static function diq_initialize_options() {

            if('' === get_option('diq_rest_key', '')){
                update_option('diq_rest_key', '');
            }

            if('' === get_option('sc_style', '')){
                update_option('sc_style', '');
            }

            if('' === get_option('sc_redirect_url', '')){
                update_option('sc_redirect_url', '');
            }
            if('' === get_option('response_status')){
                update_option('response_status', '');
            }
            if('' === get_option('sc_hide_form')){
                update_option('diq_date', '');
            }

        }

        /**
         * Add/Create table for maintaining entries of forms and shortcodes 
         * */
        public static function diq_create_table(){
            global $wpdb;
            $table = $wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}diq_forms'");

            // create DB table if it doesn't exist
            if ($table != $wpdb->prefix . 'diq_forms') {
                $diq_charset_collate = $wpdb->get_charset_collate();

                $diq_query_create_forms_table = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}diq_forms (
                    id int NOT NULL AUTO_INCREMENT,
                    diq_form_label varchar(255) NULL,
                    diq_form_html text NOT NULL,
                    diq_form_shortcode varchar(255) NOT NULL,
                    diq_form_appearance varchar(255) NULL,
                    diq_form_message text NULL,
                    diq_form_setting varchar(1000) NULL,
                    diq_form_checkbox_label varchar(255) NULL,
                    diq_form_publish_date varchar(255) NOT NULL,
                    diq_form_custom_css varchar(1000) NULL,
                    PRIMARY  KEY  (id)) $diq_charset_collate;";

                require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
                dbDelta($diq_query_create_forms_table);
            }
        }

        /**
         * Remove the options from database
         * */
        public static function diq_remove_options() {
            // get the created options
            $diq_options = array(

                'diq_rest_key',
                'sc_style',
                'sc_redirect_url',
                'response_status',
                'sc_hide_form',
            
            );

            // delete each one from the option table
            foreach($diq_options as $option){
                delete_option($option);
            }
        }

        /**
         * Remove table from database
         * */
        public static function diq_remove_table() {
            global $wpdb;
            $table = $wpdb->prefix . 'diq_forms';
            $wpdb->query( "DROP TABLE IF EXISTS $table" );
        }

        /**
         * Handle user-end scripts and styles
         * */
        public static function diq_enqueue_user_scripts_styles() {
            wp_enqueue_style( 'directIQstyle_admin',  DIRECTIQ_PLUGIN_DIR_URL . 'css/style.css', array(), '1.0' );
            wp_enqueue_style( 'directIQ_admin_css',  DIRECTIQ_PLUGIN_DIR_URL . 'css/directiq_admin.css', array(), '1.0' );
            wp_enqueue_style( 'bootstrap_style',  DIRECTIQ_PLUGIN_DIR_URL . 'css/bootstrap.min.css', array(), '1.0');
            wp_enqueue_style( 'directIQstyle_main',  DIRECTIQ_PLUGIN_DIR_URL . 'css/front.php', array(), '1.0');

            wp_enqueue_script( 'directIQscript_jquery',  DIRECTIQ_PLUGIN_DIR_URL . 'js/jquery.min.js');
            wp_enqueue_script( 'directIQscript_serialize',  DIRECTIQ_PLUGIN_DIR_URL . 'js/serialize.js' );

            wp_enqueue_script( 
                'diq-sc-form-process',
                DIRECTIQ_PLUGIN_DIR_URL . 'js/diq_sc_form_process.js',
                array('jquery'),
                '',
                false
            );
            wp_localize_script( 
                'diq-sc-form-process', 
                'diq_front_sc_form', 
                array( 
                    'ajax_url' => admin_url( 'admin-ajax.php' ) 
                ) 
            );

        }

        /**
         * Handle admin-end scripts and styles
         * */
        public static function diq_enqueue_admin_scripts_styles() {
            wp_enqueue_style( 'directIQ_admin_css',  DIRECTIQ_PLUGIN_DIR_URL . 'css/directiq_admin.css', array(), '1.0' );
            $current_page = isset($_GET['page']) ? $_GET['page'] : 0;
            $diq_api_key = get_option( 'diq_rest_key' );

            if ($current_page === "directiq" || $current_page === "directiq_add_form" || $current_page === "directiq_forms" ) {
                wp_enqueue_style( 
                    'directIQstyle_admin', #$handle
                    DIRECTIQ_PLUGIN_DIR_URL.'css/style.css', #$src
                );

                wp_enqueue_script( 
                    'diq-sc-form',  
                    DIRECTIQ_PLUGIN_DIR_URL . 'js/diq_add_form.js', 
                    array('jquery'), 
                    '1.0' 
                );
            }

            // if ($current_page === "directiq" && (!$diq_api_key || empty($diq_api_key)) ) {
            if ($current_page === "directiq") {
                wp_enqueue_script( 
                    'diq_connection',  
                    DIRECTIQ_PLUGIN_DIR_URL . 'js/diq_connect.js',
                    array('jquery'),
                    ''
                );
                wp_localize_script( 
                    'diq_connection', 
                    'diq_con_api', 
                    array( 
                        'ajax_url' => admin_url( 'admin-ajax.php' ),
                        'check_nonce' => wp_create_nonce('connect-api-nonce')
                    ) 
                );
            } 

            if ($current_page === "directiq") {

                wp_enqueue_script( 
                    'diq_connection',  
                    DIRECTIQ_PLUGIN_DIR_URL . 'js/diq_edit_shortcode.js',
                    array('jquery'),
                    ''
                );
            }

            if ($current_page === 'directiq_forms') {
                wp_enqueue_script( 
                    'diq-sc-form',  
                    DIRECTIQ_PLUGIN_DIR_URL . 'js/diq_add_form.js', 
                    array('jquery'), 
                    '1.0' 
                );

                wp_enqueue_script( 
                    'diq-sc-copy',  
                    DIRECTIQ_PLUGIN_DIR_URL . 'js/copy_shortcode.js', 
                    array('jquery'), 
                    '1.0' 
                );

                 wp_enqueue_script( 
                    'diq-duplicate-form',  
                    DIRECTIQ_PLUGIN_DIR_URL . 'js/diq_duplicate_data.js', 
                    array('jquery'), 
                    '1.0' 
                );

                wp_localize_script( 
                    'diq-sc-form', 
                    'diq_sc_form', 
                    array( 
                        'ajax_url' => admin_url( 'admin-ajax.php' ),
                        'diq_flist' => menu_page_url('directiq_forms', false)
                    ) 
                );

                wp_localize_script( 
                    'diq-duplicate-form', 
                    'diq_duplicate_form', 
                    array( 
                        'ajax_url' => admin_url( 'admin-ajax.php' ),
                        'diq_flist' => menu_page_url('directiq_forms', false)
                    ) 
                );
            }

            if ( $current_page === 'directiq_add_form' ) {
                // wp_enqueue_style( 
                //     'diq-admin', 
                //     DIRECTIQ_PLUGIN_DIR_URL.'css/admin.php',
                //     array()
                // );
                wp_enqueue_style( 
                    'diq-main-style',
                    DIRECTIQ_PLUGIN_DIR_URL.'css/main.css'
                );
                wp_enqueue_style( 
                    'diq-codemirror', 
                    DIRECTIQ_PLUGIN_DIR_URL.'codemirror/lib/codemirror.css'
                );
                wp_enqueue_style( 
                    'diq-codemirror-theme-idea',  
                    DIRECTIQ_PLUGIN_DIR_URL.'codemirror/theme/idea.css'
                );
                

                wp_enqueue_script( 
                    'diq-jquery-min', 
                    DIRECTIQ_PLUGIN_DIR_URL.'js/jquery.min.js',
                    array('jquery'),
                    '3.6.0'
                );

                wp_enqueue_script( 
                    'diq-bootstrap-min', 
                    DIRECTIQ_PLUGIN_DIR_URL.'js/bootstrap.min.js',
                    array('jquery'),
                    '',
                    false
                );

                wp_enqueue_script( 
                    'diq-codemirror',  
                    DIRECTIQ_PLUGIN_DIR_URL.'codemirror/lib/codemirror.js',
                    array('jquery'),
                    '',
                    false
                );

                wp_enqueue_script( 
                    'diq-codemirror-autorefresh',  
                    DIRECTIQ_PLUGIN_DIR_URL.'codemirror/addon/display/autorefresh.js',
                    array('jquery'),
                    '',
                    false
                );

                wp_enqueue_script(
                    'diq_codemirror-xml',
                    DIRECTIQ_PLUGIN_DIR_URL.'codemirror/mode/xml/xml.js',
                    array('jquery'),
                    '',
                    false
                );

                wp_enqueue_script( 
                    'diq-codemirror-closetag',  
                    DIRECTIQ_PLUGIN_DIR_URL.'codemirror/addon/edit/closetag.js',
                    array('jquery'),
                    '',
                    false 
                );

                wp_enqueue_script( 
                    'diq-sc-form',  
                    DIRECTIQ_PLUGIN_DIR_URL . 'js/diq_add_form.js', 
                    array('jquery'), 
                    '1.0' 
                );

                wp_enqueue_script( 
                    'diq-message',
                    DIRECTIQ_PLUGIN_DIR_URL . 'js/message.js',
                    array('jquery'),
                    '',
                    false
                );

                wp_enqueue_script( 
                    'diq-settings',
                    DIRECTIQ_PLUGIN_DIR_URL.'js/settings.js',
                    array('jquery'),
                    '',
                    false
                );

                wp_enqueue_script( 
                    'diq-form',  
                    DIRECTIQ_PLUGIN_DIR_URL.'js/diq_modal_form.js',
                    array('jquery'), 
                    '', 
                    false 
                );

                wp_enqueue_script( 
                    'diq-serialize',  
                    DIRECTIQ_PLUGIN_DIR_URL . 'js/serialize.js',
                    array('jquery'),
                    '',
                    false
                );  

                wp_enqueue_script(
                    'diq-appearance',
                    DIRECTIQ_PLUGIN_DIR_URL.'js/appearance.js',
                    array('jquery'),
                    '',
                    false
                );

                wp_localize_script( 
                    'diq-sc-form', 
                    'diq_sc_form', 
                    array( 
                        'ajax_url' => admin_url( 'admin-ajax.php' ),
                        'diq_flist' => menu_page_url('directiq_forms', false)
                    ) 
                );
                wp_localize_script( 
                    'diq-message', 
                    'save_message', 
                    array( 
                        'ajax_url' => admin_url( 'admin-ajax.php' ) 
                    ) 
                );

                wp_localize_script( 
                    'diq-settings', 
                    'save_settings', 
                    array( 
                        'ajax_url' => admin_url( 'admin-ajax.php' ) 
                    ) 
                );

                wp_localize_script( 
                    'diq-appearance', 
                    'save_style', 
                    array( 
                        'ajax_url' => admin_url( 'admin-ajax.php' ) 
                    ) 
                );
            }
        }

        /**
         * Save newly created shortcode form
         * */
        public static function diq_sc_form_submit(){
            $response = array();
            if (isset($_POST['f_label'])) {
                $sc_form_html = stripslashes($_POST['f_html']);
                $sc_form_name = sanitize_text_field($_POST['f_label']);

                // Form Messages
                $sc_form_success_msg = stripslashes($_POST['f_success_msg']);
                $sc_form_email_msg = stripslashes($_POST['f_email_msg']);
                $sc_form_required_field_msg = stripslashes($_POST['f_required_field_msg']);
                $sc_form_general_error_msg = stripslashes($_POST['f_general_error_msg']);

                $diq_form_messages = array(
                    "success_msg" => html_entity_decode($sc_form_success_msg),
                    "email_msg" => html_entity_decode($sc_form_email_msg),
                    "required_field_msg" => html_entity_decode($sc_form_required_field_msg),
                    "general_error_msg" => html_entity_decode($sc_form_general_error_msg),
                );
                $diq_form_messages = json_encode($diq_form_messages);
                // Form Messages


                // Form General Settings
                if (intval($_POST['f_subscribe_list_size']) > 0 && !is_null($_POST['f_subscribe_list_checkbox'])) {
                    $sc_form_subscribe_list_checkbox = trim(implode(",", $_POST['f_subscribe_list_checkbox']));
                }else{
                    $sc_form_subscribe_list_checkbox = intval($_POST['f_subscribe_list_size']);
                }

                $sc_form_hide_form_btn = intval($_POST['f_hide_form_btn']);

                $sc_form_redirect_url_front = $_POST['f_redirect_url_front'];

                $diq_form_settings = array(
                    "subscribe_list_checkbox" => $sc_form_subscribe_list_checkbox,
                    "hide_form_btn" => $sc_form_hide_form_btn,
                    "redirect_url_front" => $sc_form_redirect_url_front,
                );
                $diq_form_settings = json_encode($diq_form_settings);

                if (intval($_POST['f_subscribe_list_size']) > 0 && !is_null($_POST['f_subscribe_list_checkbox'])) {
                    $diq_form_checkbox_label = implode(", ",$_POST['f_subscribe_list_label_checkbox']);
                }else{
                    $diq_form_checkbox_label = intval($_POST['f_subscribe_list_size']);
                } 
                
                // Form General Settings

                // Form Appearance
                $sc_form_sc_form_appearance = $_POST['f_sc_form_appearance'];
                // update_option( 'styleform', $sc_form_sc_form_appearance , true);
                $diq_sc_custom_css = $_POST['f_diq_sc_custom_css_appearance'];
                // Form Appearance

                $diq_sc_date = $_POST['f_sc_date'];

                global $wpdb;
                $diq_form_table = $wpdb->prefix . 'diq_forms';

                if (isset($_POST['f_edit']) && $_POST['f_edit'] === "add") {
                    $insert_form_html = $wpdb->insert( 
                        $diq_form_table, 
                        array( 
                            'diq_form_html' => $sc_form_html, 
                            'diq_form_label' => $sc_form_name,
                            'diq_form_appearance' => $sc_form_sc_form_appearance,
                            'diq_form_message' => $diq_form_messages,
                            'diq_form_setting' => $diq_form_settings,
                            'diq_form_checkbox_label' => $diq_form_checkbox_label,
                            'diq_form_custom_css' => $diq_sc_custom_css,
                            'diq_form_publish_date'=> $diq_sc_date
                        ), 
                        array( 
                            '%s',
                            '%s',
                            '%s',
                            '%s',
                            '%s',
                            '%s',
                        ) 
                    );

                    if ($insert_form_html || !$wpdb->last_error) {
                        $new_sc_id = $wpdb->insert_id;
                        $form_shortcode = '[directiq_form id="'.$new_sc_id.'"]';

                        // update column with id
                        $update_form_shortcode = $wpdb->update( 
                            $diq_form_table, 
                            array( 
                                'diq_form_shortcode' => $form_shortcode, 
                            ), 
                            array( 'ID' => $new_sc_id ), 
                            array( 
                                '%s',
                            ), 
                            array( '%d' ) 
                        );

                        if (!$update_form_shortcode || !$wpdb->last_error) {
                            $response['status'] = 1;
                        }else{
                            $response['status'] = 0;
                        }
                    }else{
                        $response['status'] = 0;
                    }
                }else{
                    // edit entry
                    $edit_sc_id = intval($_POST['f_edit']);

                    $update_form_shortcode = $wpdb->update( 
                        $diq_form_table, 
                        array( 
                            'diq_form_html' => $sc_form_html, 
                            'diq_form_label' => $sc_form_name,
                            'diq_form_appearance' => $sc_form_sc_form_appearance,
                            'diq_form_message' => $diq_form_messages,
                            'diq_form_setting' => $diq_form_settings,
                            'diq_form_checkbox_label' => $diq_form_checkbox_label,
                            'diq_form_custom_css' => $diq_sc_custom_css,
                            'diq_form_publish_date'=> $diq_sc_date

                        ), 
                        array( 'id' => $edit_sc_id ), 
                        array( 
                            '%s',
                            '%s',
                            '%s',
                            '%s',
                            '%s',
                        ), 
                        array( '%d' ) 
                    );

                    if (!$update_form_shortcode || !$wpdb->last_error) {
                        $response['status'] = 1;
                    }else{
                        $response['status'] = 0;
                    }
                }
            }else{
                $response['status'] = 0;
            }
            wp_send_json($response);
            exit;
        }

        /**
         * Save Api Information to setup connection
         * */
            public static function diq_process_api_info_handle(){
            // define blank array 
            $response = array();
            // Verify
            if (
                isset($_POST['security']) && 
                wp_verify_nonce( $_REQUEST['security'], "connect-api-nonce") &&  
                isset($_POST['key']) && 
                !empty($_POST['key']) 
            ) {
                // check key
                $inp_api_key = isset($_POST['key']) ? $_POST['key'] : 0;
                // code...
                $rest_base_url = trailingslashit(esc_url_raw(DIRECTIQ_REST_BASE_URL));

                // Authorize api key 
                $rest_url = $rest_base_url.'authorize';
                $args = array(
                    "headers" => [
                        "x-api-key" => $inp_api_key,
                        "Content-Type" => "application/json, charset=utf-8"
                    ]
                );
                $authorize_info = wp_remote_get( $rest_url, $args );
                $authorize_response_code = wp_remote_retrieve_response_code($authorize_info);
                // $authorize_response_code = 400;
                if ( is_wp_error( $authorize_info ) ) {
                    $error_message = $authorize_info->get_error_message();
                    $response['status'] = 0;
                    $response['text'] = $error_message;
                    $response['span'] = "#ff0000";
                    update_option('response_status', $response['status']);
                }elseif($authorize_response_code === 200){
                    // get option value
                    $existing_key = get_option( 'diq_rest_key' );

                    // if input available and key not exists then add
                    if ($existing_key) {
                        $response['status'] = 6;
                        update_option('response_status', $response['status']);
                    }elseif ($inp_api_key && (!$existing_key || empty($existing_key))) {
                        update_option( 'diq_rest_key', esc_sql($inp_api_key) , true);
                        $response['status'] = 1;
                        $response['span'] = "#1ab41a";
                        $response['text'] = ucwords("Processing api key. Please wait!");
                        update_option('response_status', $response['status']);
                    }elseif(!empty($inp_api_key)) {
                        // input empty
                        $response['status'] = 0;
                        $response['span'] = "#ff0000";
                        $response['text'] = "Cannot process the request. Please try again!";
                        update_option('response_status', $response['status']);
                    }else{
                        // key avialble
                        $response['text'] = ucwords("API key already exist. Redirecting Please wait!");  
                        $response['status'] = 2;
                        $response['span'] = "#1ab41a";
                        
                        update_option('response_status', $response['status']);
                    }
                }else{
                    $response['status'] = 5;
                    $response['span'] = "#ff0000";
                    $notice =  self::diq_plugin_admin_notice();
                    $response['html'] = $notice;
                    $response['notice'] = '<input type="button" name="edit" class="edit button" id="diq_edit_api_info" value="Edit">';
                    update_option('response_status', '5');
   
                }
                // Authorize api key ends 
            }else{
                $response['status'] = 3;
                $response['span'] = "#ff0000";
                $response['text'] = ucwords("Cannot process the request. Please try again!");
                update_option('response_status', $response['status']);
            }
            
            wp_send_json($response);
            exit();
        }

        public static function diq_plugin_admin_notice()
        {    
            $response = get_option('response_status');

            if ($response == '' || $response == '5' ) { 
                $admin =   '<div class="notice notice-error is-dismissible">
                        <p>The Key is not a proper DirectIQ API Subsciption key. <a href="#">
                        Click here to learn how to get the correct key.</a></p>
                        </div>';
            update_option('response_status', '');
            } else {
                $url = get_admin_url();
                $admin = '<div class="notice notice-success is-dismissible">
                           <p>Successfully connected! <a href="'.$url.'admin.php?page=directiq_add_form"> Now go and create your first subscription form.</a></p>
                           </div>';
            }
            
             return $admin;
             exit();                   
        }

        
        /**
         * Remove entry from database
         * */
        public static function diq_handle_short_code_remove(){
            if (isset($_POST['rec_id'])) {
                $record = intval($_POST['rec_id']);
                global $wpdb;
                $form_table = $wpdb->prefix . 'diq_forms';

                $query = $wpdb->prepare("DELETE FROM {$form_table} WHERE `id` = %d", $record);
                $result = $wpdb->query($query);

                if ($result) {
                    wp_send_json(
                        array(
                            'status' => 1
                        )
                    );
                }else{
                    wp_send_json(
                        array(
                            'status' => 1
                        )
                    );
                }
            }
        }

        /**
         * Remove multiple entries from database
         * */
        public static function diq_multiple_short_code_remove(){
            if (isset($_POST['rec_checked_id'])) {
                $rec_id = $_POST['rec_checked_id'];
                $multipleRecord = implode(', ', $rec_id);
                 global $wpdb;
                $form_table = $wpdb->prefix . 'diq_forms';

                $query = $wpdb->get_results("DELETE FROM $form_table WHERE `id` IN ($multipleRecord)",);
                $result = $wpdb->query($query);

                if ($result) {
                    wp_send_json(
                        array(
                            'status' => 1
                        )
                    );
                }else{
                    wp_send_json(
                        array(
                            'status' => 1
                        )
                    );
                }
            }
        }

         /**
         * duplicate entry from database
         * */

        function diq_duplicate_form_record(){

            $diq_d_id = $_POST['d_current_id'];
            global $wpdb; 
            $diq_forms_table = $wpdb->prefix . 'diq_forms';
            $diq_form_duplicate_record = $wpdb->get_row( $wpdb->prepare("SELECT `diq_form_label`, `diq_form_html`, `diq_form_appearance`, `diq_form_message`, `diq_form_setting`,`diq_form_checkbox_label`,`diq_form_custom_css` FROM {$diq_forms_table} WHERE `id` = '%s'",$diq_d_id),ARRAY_A );
            if (!is_null($diq_form_duplicate_record)) {
                $duplicate_label = $diq_form_duplicate_record['diq_form_label'];
                $duplicate_form_label = $duplicate_label."_(duplicate)";
                $duplicate_form_html = $diq_form_duplicate_record['diq_form_html'];
                $duplicate_form_appearance = $diq_form_duplicate_record['diq_form_appearance'];
                $duplicate_form_messages = $diq_form_duplicate_record['diq_form_message'];
                $duplicate_form_settings = $diq_form_duplicate_record['diq_form_setting'];
                $duplicate_form_checkbox_label = $diq_form_duplicate_record['diq_form_checkbox_label'];
                $duplicate_form_custom_css = $diq_form_duplicate_record['diq_form_custom_css'];
                $duplicate_form_date = $_POST['d_sc_date'];
                global $wpdb;
                $form_table = $wpdb->prefix . 'diq_forms';

                $duplicate_insert_form_html = $wpdb->insert(
                    $form_table,
                    array(
                        'diq_form_html' => $duplicate_form_html,
                        'diq_form_label' => $duplicate_form_label,
                        'diq_form_appearance' => $duplicate_form_appearance,
                        'diq_form_message' => $duplicate_form_messages,
                        'diq_form_setting' => $duplicate_form_settings,
                        'diq_form_checkbox_label' => $duplicate_form_checkbox_label,
                        'diq_form_custom_css' => $duplicate_form_custom_css,
                        'diq_form_publish_date' => $duplicate_form_date
                    ),
                    array(
                        '%s',
                        '%s',
                        '%s',
                        '%s',
                        '%s',
                        '%s',
                        '%s',
                        '%s',
                    )
                );

                if ($duplicate_insert_form_html || !$wpdb->last_error) {
                        $new_duplicate_sc_id = $wpdb->insert_id;
                        $duplicate_form_shortcode = '[directiq_form id="'.$new_duplicate_sc_id.'"]';

                        // update column with id
                        $update_duplicate_form_shortcode = $wpdb->update( 
                            $form_table, 
                            array( 
                                'diq_form_shortcode' => $duplicate_form_shortcode, 
                            ), 
                            array( 'ID' => $new_duplicate_sc_id ), 
                            array( 
                                '%s',
                            ), 
                            array( '%d' ) 
                        );

                        if (!$update_duplicate_form_shortcode || !$wpdb->last_error) {
                            $response['status'] = 1;
                        }else{
                            $response['status'] = 0;
                        }
                    }else{
                        $response['status'] = 0;
                    }
                
                // exit();
            }
         
        }

        /**
         * Shortcode processing function for front-end directiq custom form
         * */
        public static function diq_shortcode_process_funciton($attr){
            $args = shortcode_atts( array(
                'id' => '',
            ), $attr );

            global $wpdb;
            $diq_forms_table = $wpdb->prefix . 'diq_forms';
            $diq_form_id = $args['id'];
            $diq_form_row_data = $wpdb->get_row( $wpdb->prepare("SELECT `id`, `diq_form_label`, `diq_form_html`, `diq_form_appearance`, `diq_form_message`, `diq_form_setting`,`diq_form_custom_css` FROM {$diq_forms_table} WHERE `id` = '%s'",$diq_form_id),ARRAY_A );

            $html = "";
            if (!is_null($diq_form_row_data)) {
                $form_html = $diq_form_row_data['diq_form_html'];
                $form_appearance = $diq_form_row_data['diq_form_appearance'];
                update_option('sc_style',$form_appearance);
                $form_custom_css = $diq_form_row_data['diq_form_custom_css']; 

                $form_messages = json_decode($diq_form_row_data['diq_form_message'], true);
                $success_msg = !empty($form_messages['success_msg']) ? $form_messages['success_msg'] : "Thank you, your form have been successfully submitted.";
                $email_msg = !empty($form_messages['email_msg']) ? $form_messages['email_msg'] : "Please provide a valid email address. ";
                $required_field_msg = !empty($form_messages['required_field_msg']) ? $form_messages['required_field_msg'] : "Please fill in the required fields.";

                $form_settings = json_decode($diq_form_row_data['diq_form_setting'], true);
                $redirect_url_front = $form_settings['redirect_url_front'];
                update_option( 'sc_redirect_url', $redirect_url_front , true);
                $hide_form_btn = $form_settings['hide_form_btn'];
                update_option( 'sc_hide_form', $hide_form_btn , true);
                $subscribe_listid = $form_settings['subscribe_list_checkbox'];

                

                if (!empty($diq_form_row_data['diq_form_html'])) {
                   
                    
                    $html .= '<div class="directiq_form_'.$diq_form_row_data['id'].'">';
                    $html .= '<p><style>'.$form_custom_css.'</style></p>';
                    $html .= '<div id="hide_succ_msg'.$diq_form_row_data['id'].'" style="display: none;color: green;">';
                    $html .= $success_msg;
                    $html .= '</div>';
                    $html .= '<form class="diq_subscriber_form_action ajax" name="diq_subscriber_form_'.$diq_form_row_data['id'].'" id="diq_form_subs_'.$diq_form_row_data['id'].'" action="#" method="post" data-form_id="'.$diq_form_row_data['id'].'">';
                    // $html = '<style>'.$form_custom_css.'</style>';
                    $html .= '<input type="hidden" name="listid" id="listid" value="'.$subscribe_listid.'">';
                    $html .= '<div id="form_html_'.$diq_form_row_data['id'].'">';
                    $html .= '<style> #form_html_'.$diq_form_row_data['id'].' p input[type="submit"]{
                                        background-color:'.$form_appearance.';
                                        color:#FFFFFF;
                                      } 
                                      #form_html_'.$diq_form_row_data['id'].' p input{
                                         border: 2px solid '.$form_appearance.';
                                      }</style>';
                    $html .= $form_html;
                    $html .= '</div>';
                    $html .= '<div>';
                    $html .= '<span id="success_msg_'.$diq_form_row_data['id'].'" style="display: none;color: green;">';
                    $html .= $success_msg;
                    $html .= '</span>';
                    $html .= '<span id="email_msg_'.$diq_form_row_data['id'].'" style="display: none;color: red;">';
                    $html .= $email_msg;
                    $html .= '</span>';
                    $html .= '<span id="required_field_msg_'.$diq_form_row_data['id'].'" style="display: none;color: red;">';
                    $html .= $required_field_msg;
                    $html .= '</span>';
                    $html .= '</div>';
                    $html .= '<span id="hide_form_'.$diq_form_row_data['id'].'" style="display: none;">';
                    $html .=  $hide_form_btn;
                    $html .= '</span>';
                    $html .= '<span id="redirect_url_form_'.$diq_form_row_data['id'].'" style="display: none;">';
                    $html .=  $redirect_url_front;
                    $html .= '</span>';
                    $html .= '</form>';
                    $html .= '</div>';
                }
            }else{
                $html = '<div class="directiq_form" style="display: none;">Invalid Direct IQ Form / Form is removed.</div>';
            }
            return $html;
        }

        /**
         * Process front-end form submission
         * */
        public static function diq_handle_front_form_submission(){

            $response = array();
            if ( 
                isset($_POST['email']) 
                // isset($_POST['firstname']) && 
                // isset($_POST['lastname']) 
            ){
                $diq_api_key = get_option('diq_rest_key');
                $rest_base_url = trailingslashit(esc_url_raw(DIRECTIQ_REST_BASE_URL));
                $rest_url = $rest_base_url.'subscribe';
                $directiq_formData = json_decode(stripslashes($_POST['directiq_formData']),true); 
                foreach ($directiq_formData as $key => $value){
                $directiq_formData[$key]['key'] =  $directiq_formData[$key]['name'];
                unset($directiq_formData[$key]['name']);
                }

                $data = array(
                    "listId"  => $_POST['listid'],
                    "email" => $_POST['email'],
                    "firstName" => $_POST['firstname'],
                    "lastName" => $_POST['lastname'],
                    "additionalFields" => $directiq_formData
                );
                
                $diq_front_data = json_encode($data, JSON_NUMERIC_CHECK);

                
                $post_response = wp_remote_post( $rest_url, array(
                    'headers'     => array(
                        "x-api-key" => $diq_api_key,
                        "Content-Type" => "application/json, charset=utf-8"
                    ),
                    'body'        => $diq_front_data,
                    )
                );

    
                $post_response_body = wp_remote_retrieve_body($post_response);
                $post_response_code = wp_remote_retrieve_response_code($post_response);

                if ( is_wp_error( $post_response ) ) {
                    $error_message = $post_response->get_error_message();
                    $response['status'] = 2;
                    $response['message'] = $error_message;
                    $response['code'] = $post_response_code;
                    $response['body'] = $post_response_body;
                } else {
                    $response['status'] = 1;
                    $response['code'] = $post_response_code;
                    $response['body'] = $post_response_body;
                    $url = get_option('sc_redirect_url');
                    $response['url'] = $url;
                    $hide = get_option('sc_hide_form');
                    $response['hide'] = $hide;
                }
            }else{
                $response['status'] = 0;
            }

            wp_send_json($response);
            exit();
        }

        /**
         * Activate plugin
         * */
        public function diq_init_plugin(){
            // add menu pages
            add_action(
                'admin_menu', 
                array(
                    $this, 
                    'diq_add_menu_pages'
                )
            );

            // add required options to database 
            $this->diq_initialize_options();

            // add table
            $this->diq_create_table();

            // enqueue user scripts and styles
            add_action(
                'wp_enqueue_scripts', 
                array(
                    $this, 
                    'diq_enqueue_user_scripts_styles'
                )
            );

            // enqueue admin scripts and styles
            add_action(
                'admin_enqueue_scripts', 
                array(
                    $this,
                    'diq_enqueue_admin_scripts_styles' 
                )
            );

            // Hooks to process ajax call for connection request
            add_action(
                "wp_ajax_diq_process_api_info", 
                array(
                    $this,
                    'diq_process_api_info_handle' 
                )
            );
            add_action( 
                "wp_ajax_nopriv_diq_process_api_info", 
                array(
                    $this,
                    'diq_process_api_info_handle' 
                )
            );

            // process shortcode form data for creating a new form
            add_action( 
                "wp_ajax_diq_sc_form_submit", 
                array(
                    $this, 
                    'diq_sc_form_submit'
                )
            );
            add_action( 
                "wp_ajax_nopriv_diq_sc_form_submit", 
                array(
                    $this,
                    "diq_sc_form_submit" 
                )
            );

            // remove shortcode form entry from table
            add_action( 
                "wp_ajax_diq_handle_short_code_remove", 
                array(
                    $this,
                    "diq_handle_short_code_remove" 
                )
            );
            add_action( 
                "wp_ajax_nopriv_diq_handle_short_code_remove", 
                array(
                    $this,
                    "diq_handle_short_code_remove" 
                )
            );

            // remove multiple shortcode form entry from table
            add_action( 
                "wp_ajax_diq_multiple_short_code_remove", 
                array(
                    $this,
                    "diq_multiple_short_code_remove" 
                )
            );
            add_action( 
                "wp_ajax_nopriv_diq_multiple_short_code_remove", 
                array(
                    $this,
                    "diq_multiple_short_code_remove" 
                )
            );

            // duplicate shortcode form entry from table
            add_action( 
                "wp_ajax_diq_duplicate_form_record", 
                array(
                    $this,
                    "diq_duplicate_form_record" 
                )
            );
            add_action( 
                "wp_ajax_nopriv_diq_duplicate_form_record", 
                array(
                    $this,
                    "diq_duplicate_form_record" 
                )
            );

            // Handle shortcode manipulation to display form
            add_shortcode( 
                'directiq_form' , 
                array(
                    $this,
                    'diq_shortcode_process_funciton' 
                )
            );

            /**
             * Hooks to process front-end form submission ajax call
             * */
            add_action( 
                "wp_ajax_diq_handle_front_form_submission", 
                array(
                    $this,
                    "diq_handle_front_form_submission" 
                )
            );
            add_action( 
                "wp_ajax_nopriv_diq_handle_front_form_submission", 
                array(
                    $this, 
                    "diq_handle_front_form_submission"
                )
            );
            add_action( 
                "admin_notices",
                array(
                    $this, 
                    "diq_plugin_admin_notice"
                )
            );
        }

        /**
         * Deactivate plugin
         * */
        public function diq_deactivate_plugin(){
            $this->diq_remove_options();
            $this->diq_remove_table();
        }
    }

    $diq_obj = InitializeDirectIq::GetInstance();
    $diq_obj->diq_init_plugin();
}