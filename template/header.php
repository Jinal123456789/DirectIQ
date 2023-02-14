<?php
	$diq_logo = DIRECTIQ_PLUGIN_DIR_URL."images/new_logo.png";
	$plugin_data = get_plugin_data(DIRECTIQ_PLUGIN_DIR_PATH . 'directiq.php');
	$plugin_version = $plugin_data['Version'];
?>
<div class="direct-header-area">
        <div class="direct-logo">
            <div class="logo">
                <a href="#">
                    <img src="<?php echo $diq_logo; ?>">
                </a>
            </div>
        </div>
         
        <div class="direct-header-title"> 
            DirectIQ Email Forms for Wordpress
        </div>
        
        <div class="direct-header-version"> 
            <i> <?php echo 'Version '.$plugin_version; ?> </i>
        </div>
</div>	

<style type="text/css">
	.direct-logo .logo img{
		width: 35px;
		height: 35px;
	}
	.direct-header-area{
		overflow: hidden;
  		background-color: #d9d9d9;
  		padding: 18px;
	}
	.direct-logo  {
		float: left;
		text-align: center;
		padding: 12px;
	}
	
	.direct-header-title {
		float: left;
		color: black;
		text-align: center;
	 	padding: 18px;
	 	text-decoration: none;
	 	font-size: 30px; 
	 	line-height: 25px;
	 	border-radius: 4px;
	}
	.direct-header-version{
			float: right;
			color: black;
			text-align: center;
		 	padding: 18px;
		 	text-decoration: none;
		 	font-size: 16px; 
		 	line-height: 25px;
	}

	#wpcontent{
		padding-left: 0px !important;
	}

	#adminmenu .wp-menu-image img {
    width: 18px;
	}

</style>
