<?php

class ContactForm_SubmissionsDownload
{
    protected $subpage_properties;

    public function __construct($subpage_properties) {

        $this->subpage_properties = $subpage_properties;
    }

    public function run() {

        add_action('admin_menu', [$this, 'add_menu_and_page']);
        add_action( 'init', [$this, 'generate_csv']);
    }

    public function add_menu_and_page() {

        add_submenu_page(
            $this->subpage_properties['parent_slug'],
            $this->subpage_properties['page_title'],
            $this->subpage_properties['menu_title'],
            $this->subpage_properties['capability'],
            $this->subpage_properties['menu_slug'],
            [$this,'render_subpage']
        );
    }

    public function generate_csv() {

        // if our nonce isn't there, or we can't verify it, bail
        if (!isset($_POST['page_nonce']) || !wp_verify_nonce($_POST['page_nonce'], 'export_submissions_nonce')) {
            return;
        }

        global $wpdb;

        $table_name = $wpdb->prefix . CONTACTFORMTABLE;

        $results = $wpdb->get_results(" SELECT * FROM $table_name", OBJECT);

        $count = count($results);

        if ($results && $count > 0) {

            $filename = 'contact_form_submissions.' . date('Y-m-d-H-i-s') . '.csv';

            header('Content-Description: File Transfer');

            header('Content-Disposition: attachment; filename=' . $filename);

            header('Content-Type: text/csv; charset=' . get_option('blog_charset'), true);

            // Set the columns
            $columns = [
                'Name',
                'E-Mail Address',
                'Telephone Number',
                'Enquiry',
                'Page Submitted From',
                'Submission Date'
            ];

            echo implode(',', $columns) . "\n";

            $array = [];

            $i = 0;

            foreach ($results as $result) {

                $array[$i]['contact_name'] = $this->clean_text($result->contact_name);
                $array[$i]['contact_email_address'] = $this->clean_text($result->contact_email_address);
                $array[$i]['contact_telephone_number'] = isset($result->contact_telephone_number) ? $this->clean_numbers($result->contact_telephone_number) : 'N/A';
                $array[$i]['contact_enquiry'] = $this->clean_text($result->contact_enquiry);
                $array[$i]['contact_page_id'] = isset($result->contact_page_id) ? get_the_title($result->contact_page_id) : 'N/A';
                $array[$i]['contact_submission_date'] = date( 'jS F Y \a\t g:i a', strtotime(  $result->contact_submission_date ));

                echo implode(',', $array[$i]) . "\n";

                $i++;
            }

            exit;
        }

    }

    /** Clean up text for outputting */
    public function clean_text($input) {

        $input = strip_tags($input);

        $input = stripslashes($input);

        $input = str_replace("’", "'", $input);

        return $input;
    }

    /** Clean up numbers for outputting  */
    public function clean_numbers($input) {

        if ($input == 0) {

            $input = '';

        }

        return $input;
    }

    public function render_subpage() {

        global $wpdb;

        $table_name = $wpdb->prefix . CONTACTFORMTABLE;

        $results = $wpdb->get_results(" SELECT * FROM $table_name", OBJECT);

        $count = count($results);

        include(plugin_dir_path(dirname(__FILE__)) . 'views/submissionDownloadView.php');
    }
}
