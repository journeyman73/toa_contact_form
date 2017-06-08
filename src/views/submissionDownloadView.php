<div class="wrap">
    <h2>Contact Form Submissions</h2>
    <?php if ($count == 0) { ?>
        <div id="message" class="updated"><p>There are no submissions to download</p></div>
    <?php } else { ?>
        <div id="poststuff">
            <div class="postbox">
                <h2><span>Download</span></h2>
                <div class="inside">
                    <form method="post" action="" enctype="multipart/form-data">
                        <?php wp_nonce_field('export_submissions_nonce', 'page_nonce'); ?>
                        <p class="submit">
                            <input type="hidden" name="_wp_http_referer" value="<?php echo $_SERVER['REQUEST_URI'] ?>"/>
                            <input type="submit" class="button-primary" value="<?php _e('Download Submissions', 'toa_contact_form'); ?>"/>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    <?php } ?>
</div>
