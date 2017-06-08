<?php
    if (isset($form_title)) {

        echo '<h3 class="form-title">' . $form_title . '</h3>';

    }

    if (isset($form_description)) {

        echo '<p class="form-description">' . $form_description . '</p>';

    }
    if (isset($message)) {
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
        <input type="email" id="contact_email_address" name="contact_email_address"
               value="<?php echo $contact_email_address; ?>"<?php echo isset($errors->contact_email_address) ? ' class="error"' : '' ?>>
        <?php if (isset($errors->contact_email_address)) { ?>
            <label id="contact_email_address_error" class="error" for="contact_email_address"><?php echo $errors->contact_email_address; ?></label>
        <?php } ?>
    </div>
    <div class="form-field">
        <label for="contact_telephone_number" class="form_label">Telephone Number</label>
        <input type="tel" id="contact_telephone_number" name="contact_telephone_number"
               value="<?php echo $contact_telephone_number; ?>"<?php echo isset($errors->contact_telephone_number) ? ' class="error"' : '' ?>>
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
    <?php if ( isset($site_key) && isset($secret_key) ) { ?>
        <div class="form-field form-required">
            <div class="g-recaptcha" data-sitekey="<?php echo $site_key; ?>"></div>
            <noscript>
                <div>
                    <div style="width: 302px; height: 422px; position: relative;">
                        <div style="width: 302px; height: 422px; position: absolute;">
                            <iframe src="https://www.google.com/recaptcha/api/fallback?k=<?php echo $site_key; ?>" frameborder="0" scrolling="no" style="width: 302px; height:422px; border-style: none;"></iframe>
                        </div>
                    </div>
                    <div style="width: 300px; height: 60px; border-style: none; bottom: 12px; left: 25px; margin: 0px; padding: 0px; right: 25px; background: #f9f9f9; border: 1px solid #c1c1c1; border-radius: 3px;">
                        <textarea id="g-recaptcha-response" name="g-recaptcha-response" class="g-recaptcha-response" style="width: 250px; height: 40px; border: 1px solid #c1c1c1; margin: 10px 25px; padding: 0px; resize: none;"></textarea>
                    </div>
                </div>
            </noscript>
            <?php if (isset($errors->recaptcha)) { ?>
                <label id="recaptcha_error" class="error" for="g-recaptcha-response"><?php echo $errors->recaptcha; ?></label>
            <?php } ?>
        </div>
    <?php } ?>
    <input type="hidden" name="contact_page_id" value="<?php echo $post->ID; ?>">
    <div class="form-submit">
        <button class="button_submit" type="submit" name="form-submitted">Send</button>
    </div>
</form>
