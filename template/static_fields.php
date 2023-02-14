<?php if($field_shortcode === 'agree'){?>
  <div class="<?php echo "diq-agree-box"; ?>">
    <a href="#diq_input_modal_agree_box" class="button" data-toggle="modal"> 
      <?php echo "Agree"; ?> 
    </a> 
  </div>
  <div id="diq_input_modal_agree_box" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-dialog modal-dialog-centered" role="document">        
        <div class="modal-content shadow">
          <div class="modal-header">
            <h5 class="modal-title"> 
              <?php echo strtoupper("add agree field"); ?>
            </h5>
            <button type="button" id="lclose" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body bg-image" style="background-image: url()">
            <input type="hidden" name="<?php echo "agree_type" ?>" id="<?php echo "agree_type"; ?>" value="<?php echo "checkbox"; ?>" form="<?php echo "form_agree_modal"; ?>">
            <div class="form-group mx-2">
              <label for="<?php echo "agree_url"; ?>"> 
                <?php echo strtoupper("policy page url"); ?>
              </label>
              <input type="text" class="form-control" id="<?php echo "agree_url"; ?>" placeholder="<?php echo ucfirst("add url for terms/policy page") ?>" autocomplete="off" form="<?php echo "form_agree_modal"; ?>">
            </div>
            <div class="form-group mx-2">
              <label for="<?php echo "agree_text"; ?>"> 
                <?php echo strtoupper("agree text"); ?>
              </label>
                <input type="text" class="form-control" id="<?php echo "agree_text"; ?>" placeholder="<?php echo ucfirst("type text to display for agree box") ?>" autocomplete="off" form="<?php echo "form_agree_modal"; ?>">
            </div>
              <button type="submit" name="save_modal" class="btn btn-primary" form="<?php echo "form_agree_modal"; ?>">
                  <?php _e(ucwords('add to form'), 'directiq') ?>
              </button>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php }
        if($field_shortcode === 'submit'){?>
          <div class="<?php echo "diq-submit-btn"; ?>" style="margin:2px;">
            <a href="<?php echo "#diq_input_modal_submit_btn"; ?>" class="button" data-toggle="modal"> 
              <?php _e(ucwords('submit'), 'directiq'); ?> 
            </a> 
          </div>
          <div id="<?php echo "diq_input_modal_submit_btn"; ?>" class="modal fade">
          <div class="modal-dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content shadow">
                <div class="modal-header">
                  <h5 class="modal-title">
                    <?php echo strtoupper("add submit field"); ?>
                  </h5>
                  <button type="button" id="<?php echo "submit_modal_close"; ?>" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
                </div>
              <div class="modal-body bg-image" style="background-image: url();">
              <input type="hidden" name="<?php echo "submit_type" ?>" id="<?php echo "submit_type"; ?>" value="<?php echo "submit"; ?>" form="<?php echo "form_submit_modal"; ?>">
                <div class="form-group mx-2">
                  <label for="<?php echo "submit_value"; ?>"> 
                    <?php _e(strtoupper('label') ,'directiq') ?>
                  </label>
                  <input type="text" class="form-control" id="<?php echo "submit_value"; ?>" value="<?php echo $field_name; ?>" placeholder="<?php _e(ucfirst('type submit button label text'),'directiq') ?>" autocomplete="off" form="<?php echo "form_submit_modal"; ?>">
                </div>
                <div class="form-group mx-2 mb-3">
                  <label for="<?php echo "submit_id"; ?>">
                    <?php _e(strtoupper('id') ,'directiq') ?>
                  </label>
                    <input type="text" class="form-control" id="<?php echo "submit_id"; ?>" placeholder="<?php _e(ucfirst('type element id'),'directiq') ?>" autocomplete="off" form="<?php echo "form_submit_modal"; ?>">
                </div>
                <div class="form-group mx-2">
                  <label for="<?php echo "submit_class"; ?>">
                    <?php _e(strtoupper('class') ,'directiq') ?>
                  </label>
                  <input type="text" class="form-control" id="<?php echo "submit_class"; ?>" placeholder="<?php _e(ucfirst('type element class name'),'directiq') ?>" autocomplete="off" form="<?php echo "form_submit_modal"; ?>">
                </div>
                  <button type="submit" name="save_modal" class="btn btn-primary" form="<?php echo "form_submit_modal"; ?>">
                    <?php _e(ucwords('add to form'), 'directiq') ?>
                  </button>
                </div> 
              </div> 
            </div>
          </div>
        </div> 
      <?php 
      }?>