<?php require('header.php'); ?>
<?php  
$cn_loader = DIRECTIQ_PLUGIN_DIR_URL."images/ajax-loader.gif";
?>
<div class="row">
<div class="column1" style="width:75%;">
<div class="diq_main_connec">
	<div class='wrap'>
		<div class="diq-add-form-title" style="padding: 0px 0px 0px 10px;margin-bottom: 20px;margin-top: 50px;">
			<?php $response = get_option('response_status');
			 if ($response == '1') { ?>
			<div class="status-admin">
				<span id="cn_api_admin_status"><?php echo InitializeDirectIq::diq_plugin_admin_notice(); ?> </span>
			</div>
			<?php update_option('response_status', '');
			} else { ?>
				<div class="status-admin">
				<span id="cn_api_admin_status"></span>
				</div>
			<?php } ?>
			<span style="font-size: 23px;font-weight: 500;margin-left: 5px;">
				<?php _e(ucwords("General Setup"), "directiq"); ?> 
			</span>
		</div>  
		<div class="main-content">
			<div class="status_div">
				<span class="success_status" style="display:none;"> 
					<?php _e("Success", "directiq"); ?> 
				</span>
				<span class="failed_status" style="display:none;">
					<?php _e("Failed", "directiq"); ?> 
				</span>
			</div>
			<div style="padding: 15px; color: black; font-size: 15px;">
				<form class="" method="post" action="#" id="save_api_form">
					<table>
						<tbody>
							<tr>
								<td>
									<div class="form-row">  
										<label>  
											<span> 
												<?php _e(ucwords("Status"), "directiq"); ?> 
											</span>  
										</label>
									</div>
								</td>

								<?php if (!$diq_api_key): ?>
									<td>
										<div class="form-row">  
											<span style='color:#ffff;font-size: 15px;font-weight: 500; background-color: #c32020; padding:5px; '> 
												<?php _e(strtoupper('Not Connected'),'directiq') ?> 
											</span> 
										</div>
									</td>
									<?php $disabled = "";
								else : ?>
									<td>
										<div class="form-row">  
											<span style="color:#ffff;font-size: 15px;font-weight: 500; background-color:green; padding:5px;">
												<?php _e(strtoupper('connected'),'directiq') ?> 
											</span>  
										</div>
									</td>
									<?php $disabled = "disabled='disabled'";
								endif; ?>	
							</tr>
							<tr>
								<td>
									<div class="form-row" style="padding-bottom: 20px;">  
										<label>  
											<span>
												<?php _e(ucwords("Your key"), "directiq"); ?>
											</span>  
										</label>			
									</div>
								</td>
								<td>
									<div class="form-row" style="padding-top: 20px;">  
										<input 
										type="text" 
										name="diq_api_key" 
										id="diq_api_key" 
										class="diq_api_key" 
										value= "<?php echo $diq_api_key; ?>"
										required
										placeholder="<?php _e(ucwords('enter your key here'), 'directiq'); ?>"
										<?php echo $disabled; ?>
										>

										<?php if ($diq_api_key){ ?>
											<span id="diq_edit_api_stts"> <input type="button" name="edit" class="edit button" id="diq_edit_api_info" value="Edit"> </span>
										<?php } else{?>
											<span id="diq_edit_api_stts"></span>
										<?php } ?>

										<p>
											This your DirectIQ API Subscription Form key. <a href="https://app.directiq.com/integrations/apikeys"  target=”_blank”> Get your key from here. </a>
										</p> 
									</div>
								</td>
							</tr>
							<tr colspan="2">
								<td>
									<div class="form-row">  
										<input type="submit" name="submit" class="button button-primary" id="diq_save_api_info" value="Save Changes">
										<img 
										src="<?php echo $cn_loader; ?>" style="display: none;" 
										class="cn_api_loader"
										width="25"
										> 
									</div>  
								</td>
							</tr>
						</tbody>
					</table>
				</form>
				<div class="status-msg">
					<span id="cn_api_stts"></span>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
<div class="column2" style="width:25%;">
	<div class="Sidebar-section">
		<?php include('sidebar.php'); ?>
	</div>
</div>
</div>
