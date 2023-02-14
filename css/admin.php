<?php header("Content-type: text/css"); ?>
<?php require_once('../../../../wp-config.php');?>
<?php
$options = get_option( 'styleform' );

    $eid = isset($_REQUEST["eid"]) ? $_REQUEST["eid"] : 0;
    global $wpdb;
    $diq_forms = $wpdb->prefix . 'diq_forms';
    $dq_edit_appearance= $wpdb->get_col("SELECT diq_form_appearance FROM $diq_forms where id='$eid'"); 
    $dq_edit_appearance = sizeof($dq_edit_appearance) > 0 ? $dq_edit_appearance[0] : "";
        if($options == "#FFFFFF"){?>
            p input[type="submit"]{
                background-color:<?php echo $options?> !important;
                color:#000000 !important;
            }
            p input[type="text"]{
                border: 2px solid <?php echo $options?> !important;
            }
            p input[type="checkbox"]{
                border: 2px solid <?php echo $options?> !important;
            }
        <?php } else{?>
            p input[type="submit"]{
                background-color:<?php echo $options?> !important;
                color:#FFFFFF !important;
            }
            p input[type="text"]{
                border: 2px solid <?php echo $options?> !important;
            }
            p input[type="checkbox"]{
                border: 2px solid <?php echo $options?> !important;
            }

        <?php   }
// }?>



p input[type="text"]{
  display: block;
  width: 100%;
  padding: .7rem 1rem;
  font-size: 1rem;
  line-height: 1.25;
  color: #464a4c;
  /* background-color: #fff; */
  background-image: none;
  background-clip: padding-box;
  border: 1px solid rgba(0, 0, 0, 0.15);
  border-radius: .2rem;
  transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
  font-family: inherit;
}
p input[type="email"]{
  display: block;
  width: 100%;
  padding: .7rem 1rem;
  font-size: 1rem;
  line-height: 1.25;
  color: #464a4c;
  /* background-color: #fff; */
  background-image: none;
  background-clip: padding-box;
  border: 1px solid rgba(0, 0, 0, 0.15);
  border-radius: .2rem;
  transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
  font-family: inherit;
}
p input[type="submit"]{
  margin-top:10px;
}