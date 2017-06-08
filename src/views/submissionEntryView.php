<div class="wrap">
    <h2><?php _e('Contact Form Submissions', 'toa_contact_form') ?> <a class="page-title-action" href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=contact-form'); ?>"><?php _e('Back to list', 'toa_contact_form') ?></a></h2>
    <?php if (!empty($notice)) { ?>
        <div id="notice" class="error"><p><?php echo $notice ?></p></div>
    <?php } ?>
    <?php if (!empty($item)) { ?>
        <div id="poststuff">
            <div class="postbox">
                <h2><span>Entry</span></h2>
                <div class="inside">
                    <table class="form-table">
                        <tbody>
                        <tr>
                            <th scope="row">Name:</th>
                            <td><?php echo $item["contact_name"]; ?></td>
                        </tr>
                        <tr>
                            <th scope="row">E-Mail Address:</th>
                            <td><?php echo $item["contact_email_address"]; ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Telephone Number:</th>
                            <td><?php echo isset($item["contact_telephone_number"]) ? $item["contact_telephone_number"] : ''; ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Enquiry:</th>
                            <td><?php echo $item["contact_enquiry"]; ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Page Submitted From:</th>
                            <td><?php echo isset($item["contact_page_id"]) ? get_the_title($item["contact_page_id"]) : ''; ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Submission Date:</th>
                            <td><?php echo date('jS F Y \a\t g:i a', strtotime($item["contact_submission_date"])); ?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php } ?>
</div>
