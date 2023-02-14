<div class="maindq">
  <div class="bs-example">
      <div class="<?php echo $field_name; ?>">
        <a href="<?php echo $href_btn_id; ?>" class="button" data-toggle="modal"> 
          <?php if ($field_shortcode === "email"){
              $field_name = "Email Address";
            echo $field_name.'<span style="color:red;"> *</span>'; 
        } else{
            echo $field_name;

        }
        ?> 
    </a> 
</div>


<div id="<?php echo $href_modal_id; ?>" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content shadow">
          <div class="modal-header">
            <h5 class="modal-title">
              <?php echo strtoupper("Add ".$field_name . " field"); ?>
          </h5>
          <button type="button" id="<?php echo $modal_close_btn; ?>" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
          </button>
      </div>
      <div class="modal-body bg-image" style="background-image: url();">
        <input type="hidden" name="<?php echo $modal_input_field_type; ?>" id="<?php echo $modal_input_field_type; ?>" value="<?php echo $field_input_type; ?>" form="<?php echo $modal_form_id; ?>">
        <div class="form-group mx-2 mb-3">
          <label for="<?php echo $modal_input_field_label; ?>">
            <?php _e(strtoupper('label') ,'directiq') ?>
        </label>
        <input type="text" class="form-control" id="<?php echo $modal_input_field_label; ?>" placeholder="<?php _e(ucfirst('type element label text'),'directiq') ?>" value="<?php echo $field_name;?>" autocomplete="off" form="<?php echo $modal_form_id; ?>">
    </div>
    <div class="form-group mx-2 mb-3">
      <label for="<?php echo $modal_input_field_id; ?>">
        <?php _e(strtoupper('id') ,'directiq') ?>
    </label>
    <input type="text" class="form-control" id="<?php echo $modal_input_field_id; ?>" placeholder="<?php _e(ucfirst('type element id'),'directiq') ?>" autocomplete="off" form="<?php echo $modal_form_id; ?>">
</div>
<div class="form-group mx-2">
  <label for="<?php echo $modal_input_field_class; ?>">
    <?php _e(strtoupper('class') ,'directiq') ?>
</label>
<input type="text" class="form-control" id="<?php echo $modal_input_field_class; ?>" placeholder="<?php _e(ucfirst('type element class name'),'directiq') ?>" autocomplete="off" form="<?php echo $modal_form_id; ?>">
</div>
<div class="form-group mx-2">
  <label for="<?php echo $modal_input_field_placeholder; ?>"> 
    <?php _e(strtoupper('placeholder') ,'directiq') ?>
</label>
<input type="text" class="form-control" id="<?php echo $modal_input_field_placeholder; ?>" value="<?php echo $field_shortcode; ?>" placeholder="<?php _e(ucfirst('type placeholder text'),'directiq') ?>"  autocomplete="off" form="<?php echo $modal_form_id; ?>">
</div>
<button type="submit" name="save_modal" class="btn btn-primary" form="<?php echo $modal_form_id; ?>">
  <?php _e(ucwords('add to form'), 'directiq') ?>
</button>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
