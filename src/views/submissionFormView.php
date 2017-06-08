<?php
    if ( isset($form_title)) {

        echo '<h3 class="form-title">' . $form_title . '</h3>';

    }

    if ( isset($form_description)) {

        echo '<p class="form-description">' . $form_description . '</p>';

    }
    if ( isset($message) ) {
       echo $message;
    }
?>
<form enctype="multipart/form-data" method="post" id="contact-form">
    <?php wp_nonce_field('contact_submission_nonce', 'page_nonce'); ?>
    <div class="form-field form-required">
        <label for="contact_name" class="form_label">Name <span class="required">(Required)</span></label>
        <input type="text" id="contact_name" name="contact_name" value="<?php echo $contact_name; ?>"<?php echo isset($errors->contact_name) ? ' class="error"' : '' ?>>
        <?php if (isset($errors->contact_name)) { ?>
            <label id="contact_name_error" class="error" for="contact_name"><?php echo $errors->contact_name; ?></label>
        <?php } ?>
    </div>
    <div class="form-field form-required">
        <label for="contact_email_address" class="form_label">E-Mail Address <span class="required">(Required)</span></label>
        <input type="email" id="contact_email_address" name="contact_email_address" value="<?php echo $contact_email_address; ?>"<?php echo isset($errors->contact_email_address) ? ' class="error"' : '' ?>>
        <?php if (isset($errors->contact_email_address)) { ?>
            <label id="contact_email_address_error" class="error" for="contact_email_address"><?php echo $errors->contact_email_address; ?></label>
        <?php } ?>
    </div>
    <div class="form-field">
        <label for="contact_telephone_number" class="form_label">Telephone Number</label>
        <input type="tel" id="contact_telephone_number" name="contact_telephone_number" value="<?php echo $contact_telephone_number; ?>"<?php echo isset($errors->contact_telephone_number) ? ' class="error"' : '' ?>>
        <?php if (isset($errors->contact_telephone_number)) { ?>
            <label id="contact_telephone_number_error" class="error" for="contact_telephone_number"><?php echo $errors->contact_telephone_number; ?></label>
        <?php } ?>
    </div>
    <div class="form-field form-required">
        <label for="contact_enquiry" class="form_label">Enquiry <span class="required">(Required)</span></label>
        <textarea name="contact_enquiry" id="contact_enquiry" rows="5"<?php echo isset($errors->contact_enquiry) ? ' class="error"' : '' ?>></textarea>
        <?php if (isset($errors->contact_enquiry)) { ?>
            <label id="contact_enquiry_error" class="error" for="contact_enquiry_address"><?php echo $errors->contact_enquiry; ?></label>
        <?php } ?>
    </div>
    <input type="hidden" name="contact_page_id" value="<?php echo $post->ID; ?>">
    <div class="form-submit">
        <button class="button_submit" type="submit" name="form-submitted">Send</button>
    </div>
</form>
