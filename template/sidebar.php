<?php 
	/**
	* Sidebar for all templates
	*
	**/         
	$current_page = isset($_GET['page']) ? $_GET['page'] : 0;
    $diq_banner = DIRECTIQ_PLUGIN_DIR_URL."images/wp-banner.jpg";
    $url = 'https://help.directiq.com/en/collections/3289205-apps-and-plugins#directiq-wordpress-plug-in';
                
if ($current_page === "directiq") {?>
<div class="diq_connec_sidebar">
<div class="diq_connec_sidebar_help">
	<a href="https://www.directiq.com/" target="_blank">
		<img src="<?php echo $diq_banner; ?>"  style="height:140px; width: 245px;">
	</a>
</div>
<div class="diq_connec_sidebar_help">
	<p><b><?php _e(ucwords("Start Here"),'directiq');?></b></p>
	<p><?php _e("Now it's easy to create email subscription forms and sync your new contacts with your DirectIQ Email Marketing account real time.",'directiq');?></p>
</div>	
<!-- <div class="diq_connec_sidebar_help">
	<p><b><?php //_e(ucwords("Help 2"),'directiq');?></b></p>
	<p><?php //_e("Body copy light. Donec sed odio dui. Cras justo odio. dapibus ac facilisis in.",'directiq');?></p>
</div> -->
<div class="diq_connec_sidebar_help">
	<a href="<?php echo $url ?>" target="_blank">
		<input type="button" id="more_help" value="More Help">
	</a>
</div>
</div>
<?php } ?>
<!-- Add new form page -->
<?php if ($current_page === "directiq_add_form") {?>
<div class="diq_new_form_sidebar">
<div class="diq_new_form_sidebar_help">
	<p><b><?php _e(ucwords("Fileds help"),'directiq');?></b></p>
	<p><?php _e("Create your form by dragging and dropping form objects. You can also preview and edit the generated HTML code.",'directiq');?></p>
	<p><a href="https://help.directiq.com/en/articles/5866480-create-a-subscription-form-with-wordpress-plugin#h_a3168c559f" target="_blank"><?php _e("Learn More",'directiq');?></a></p>
</div>
<div class="diq_new_form_sidebar_help">
	<p><b><?php _e(ucwords("Messages help"),'directiq');?></b></p>
	<p><?php _e("Modify your form’s success and error messages under Messages tab.",'directiq');?></p>
	<p><a href="https://help.directiq.com/en/articles/5866480-create-a-subscription-form-with-wordpress-plugin#h_b8b622f970" target="_blank"><?php _e("Learn More",'directiq');?></a></p>
</div>
<div class="diq_new_form_sidebar_help">
	<p><b><?php _e(ucwords("Settings help"),'directiq');?></b></p>
	<p><?php _e("Settings tab will let you connect your form to your DirectIQ contact lists.",'directiq');?></p>
	<p><a href="https://help.directiq.com/en/articles/5866480-create-a-subscription-form-with-wordpress-plugin#h_5297217558" target="_blank"><?php _e("Learn More",'directiq');?></a></p>
</div>
<div class="diq_new_form_sidebar_help">
	<p><b><?php _e(ucwords("Appearance help"),'directiq');?></b></p>
	<p><?php _e("Your form will match your website’s look and feel by default. You can alter this under Appearance tab.",'directiq');?></p>
	<p><a href="https://help.directiq.com/en/articles/5866480-create-a-subscription-form-with-wordpress-plugin#h_d7c4da5b38" target="_blank"><?php _e("Learn More",'directiq');?></a></p>
</div>
</div>
<?php } ?>
<!-- All forms -->
<?php if ($current_page === "directiq_forms" ) {?>
<div class="diq_all_forms_sidebar" style="margin: auto;">
<div class="diq_all_forms_sidebar_help">
	<p><b><?php _e(ucwords("How to Publish A Form?"),'directiq');?></b></p>
	<p><?php _e("Here you can list, duplicate or delete your forms. Just copy and insert the provided shortcode on any page or sidebar widget on your website to publish it.",'directiq');?></p>
	<p><a href="https://help.directiq.com/en/articles/5866849-publish-your-form" target="_blank"><?php _e("Learn More",'directiq');?></a></p>	
</div>
<!-- <div class="diq_all_forms_sidebar_help">
	<p><b><?php //_e(ucwords("Form Fields help"),'directiq');?></b></p>
	<p><?php //_e("Body copy light. Donec sed odio dui. Cras justo odio. dapibus ac facilisis in dodos gilde.",'directiq');?></p>
	<p><a href="<?php //echo $url ?>/wp-admin/admin.php?page=directiq_faq"><?php //_e("Learn More",'directiq');?></a></p>
</div> -->
</div>
<?php }?>