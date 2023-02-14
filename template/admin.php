<?php
$edit_id = isset($_GET['eid']) ? $_GET['eid'] : 0;
 global $wpdb;
 $diq_forms = $wpdb->prefix . 'diq_forms';
    $appearance_value = $wpdb->get_row( $wpdb->prepare("SELECT `diq_form_appearance` FROM $diq_forms WHERE `id` = '%s'",$edit_id),ARRAY_A );
    $color = $appearance_value['diq_form_appearance'];
        if($color == "#FFFFFF"){?>
          <style>
            p input[type="submit"]{
                background-color:<?php echo $color?> !important;
                color:#000000 !important;
            }
            p input[type="text"]{
                border: 2px solid <?php echo $color?> !important;
            }
            p input[type="checkbox"]{
                border: 2px solid <?php echo $color?> !important;
            }
          </style>
        <?php } else{?>
          <style>
            p input[type="submit"]{
                background-color:<?php echo $color?> !important;
                color:#FFFFFF !important;
            }
            p input[type="text"]{
                border: 2px solid <?php echo $color?> !important;
            }
             p input[type="email"]{
                border: 2px solid <?php echo $color?> !important;
            }
            p input[type="checkbox"]{
                border: 2px solid <?php echo $color?> !important;
            }
          </style>
        <?php   }