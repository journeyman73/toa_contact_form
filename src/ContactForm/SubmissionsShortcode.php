<?php

    class ContactForm_SubmissionsShortcode
    {

        protected $shortcode_properties;

        public function __construct($shortcode_properties) {

            $this->shortcode_properties = $shortcode_properties;
        }

        public function run() {

            add_shortcode($this->shortcode_properties['shortcode_name'], [$this, 'render_shortcode']);
            add_action('wp_enqueue_scripts', [$this, 'toa_contact_form_scripts_scripts'], 11);
        }

        public function toa_contact_form_scripts_scripts($hook) {

            $site_key = get_option('contact_form_recaptcha_site_key');
            $secret_key = get_option('contact_form_recaptcha_secret_key');

            wp_enqueue_style('styles', plugins_url('../assets/css/toa-contact-form-styles.css', __FILE__));
            wp_enqueue_script('scripts', plugins_url('../assets/js/toa-contact-form-scripts.min.js', __FILE__), ['jquery'], false, true);

            if ( isset($site_key) && isset($secret_key) ) {
                wp_enqueue_script( 'google-recaptcha', 'https://www.google.com/recaptcha/api.js', [], '2.0', true );
            }
        }

        /* Handles the rendering of shortcode */
        public function render_shortcode() {

            if (isset($_POST['form-submitted']) && (isset($_POST['page_nonce']) || wp_verify_nonce($_POST['page_nonce'], 'contact_submission_nonce'))) {

                $errors = $this->form_validation();

                if (empty((array)$errors)) {

                    $result = $this->add_submission_to_db();

                    if ($result == 1) {

                        $_POST = [];

                        $this->display_contact_form($this->display_message(), NULL);

                    } elseif ( empty($result) ){

                        $_POST = [];

                        $message = '<h4 class="success-message">Your enquiry could not be submitted, please notify the website administrator</h4>';

                        $this->display_contact_form($message, NULL);
                    }

                } else {

                    $this->display_contact_form(NULL, $errors);
                }

            } else {

                $this->display_contact_form( NULL, NULL);
            }
        }

        /* Handles the form and content */
        private function display_contact_form($message = null, $errors = NULL) {

            global $post;

            $contact_name = (isset($_POST["contact_name"])) ? $_POST["contact_name"] : "";
            $contact_email_address = (isset($_POST["contact_email_address"])) ? $_POST["contact_email_address"] : "";
            $contact_telephone_number = (isset($_POST["contact_telephone_number"])) ? $_POST["contact_telephone_number"] : "";
            $contact_enquiry = (isset($_POST["contact_enquiry"])) ? $_POST["contact_enquiry"] : "";
            $contact_page_id = (isset($_POST["contact_page_id"])) ? $_POST["contact_page_id"] : "";

            $form_title = get_option('contact_form_title');
            $form_description = get_option('contact_form_description');
            $site_key = get_option('contact_form_recaptcha_site_key');
            $secret_key = get_option('contact_form_recaptcha_secret_key');

            include(plugin_dir_path(dirname(__FILE__)) . 'views/submissionFormView.php');

        }

        /* Validate form */
        private function form_validation() {

            $form_errors = new stdClass();

            if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])){

                $secret_key = get_option('contact_form_recaptcha_secret_key');

                $verify_response = wp_remote_get('https://www.google.com/recaptcha/api/siteverify?secret=' . $secret_key . '&response=' . $_POST['g-recaptcha-response']);

                $verify_response = wp_remote_retrieve_body($verify_response);

                $response_data = json_decode($verify_response );

                if($response_data->success == false ) {

                    $form_errors->recaptcha = 'Robot verification failed, please try again';
                }

            } elseif(isset($_POST['g-recaptcha-response']) && empty($_POST['g-recaptcha-response'])){

                $form_errors->recaptcha = 'Please click on the reCAPTCHA box';

            }

            if (isset($_POST["contact_name"]) && trim($_POST['contact_name']) === '') {

                $form_errors->contact_name = 'Please enter your name';

            } elseif (isset($_POST["contact_name"]) && !preg_match("/^[a-zA-Z ]*$/", $_POST['contact_name'])) {

                $form_errors->contact_name = "Only letters and white spaces allowed";
            }

            if (isset($_POST["contact_email_address"]) && trim($_POST['contact_email_address']) === '') {

                $form_errors->contact_email_address = 'Please enter an email address';

            } elseif (isset($_POST["contact_email_address"]) && !filter_var($_POST['contact_email_address'], FILTER_VALIDATE_EMAIL)) {

                $form_errors->contact_email_address = "Please enter a valid email address";
            }

            if (isset ($_POST["contact_telephone_number"]) && $_POST["contact_telephone_number"] != null) {

                if (!preg_match("/^[0-9\040\.\-+()]+$/i", trim($_POST['contact_telephone_number']))) {

                    $form_errors->contact_telephone_number = "Please enter a valid phone number";

                }

            }

            if (isset($_POST["contact_enquiry"]) && trim($_POST['contact_enquiry']) === '') {

                $form_errors->contact_enquiry = 'Please enter an enquiry';

            }

            return $form_errors;
        }

        /* Submit clean data to custom table */
        private function add_submission_to_db() {

            global $wpdb;

            // Set the db table name variable
            $table_name = $wpdb->prefix . CONTACTFORMTABLE;

            // Set the default items
            $submission_defaults = [
                'contact_name'             => '',
                'contact_email_address'    => '',
                'contact_telephone_number' => '',
                'contact_enquiry'          => '',
                'contact_page_id'          => '',
            ];

            foreach ($_POST as $key => $value) {

                $_POST[$key] = sanitize_text_field($value);

            }

            // Combines posted data with known attributes and fills in defaults when needed
            $submission_item = shortcode_atts($submission_defaults, $_POST);

            // Set Data
            $submission_item = [
                'contact_name'             => $submission_item['contact_name'],
                'contact_email_address'    => $submission_item['contact_email_address'],
                'contact_telephone_number' => $submission_item['contact_telephone_number'],
                'contact_enquiry'          => $submission_item['contact_enquiry'],
                'contact_page_id'          => $submission_item['contact_page_id'],
                'contact_submission_date'  => current_time('mysql')
            ];

            $result = $wpdb->insert($table_name, $submission_item);

            return $result;
        }

        /* Handles the rendering of messages */
        private function display_message() {

            $form_message = get_option('contact_form_message');

            if (isset($form_message) && !empty($form_message)) {

                return '<h4 class="success-message">' . $form_message . '</h4>';

            } else {

                return '<h4 class="success-message">Your enquiry was successfully submitted. Thank you!</h4>';
            }
        }

    }
