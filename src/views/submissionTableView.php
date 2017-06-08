<div class="wrap">
    <h2><?php _e('Contact Form Submissions', 'toa_contact_form') ?></h2>
    <p>To add this section to a page use shortcode. <strong>[<?php echo $this->page_properties['shortcode_name']; ?>]</strong></p>

    <?php echo $message; ?>
    <form id="submissions-table" method="GET">
        <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>"/>
        <?php $table->display() ?>
    </form>
</div>
