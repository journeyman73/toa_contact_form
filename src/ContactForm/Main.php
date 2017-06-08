<?php
    defined('ABSPATH') or die("Cannot access pages directly.");

    define("CONTACTFORMTABLE", "toa_contact_form");

    class ContactForm_Main implements ArrayAccess
    {

        protected $contents;

        public function __construct() {

            $this->contents = array();
        }

        public function offsetSet($offset, $value) {

            $this->contents[$offset] = $value;
        }

        public function offsetExists($offset) {

            return isset($this->contents[$offset]);
        }

        public function offsetUnset($offset) {

            unset($this->contents[$offset]);
        }

        public function offsetGet($offset) {

            if (is_callable($this->contents[$offset])) {
                return call_user_func($this->contents[$offset], $this);
            }
            return isset($this->contents[$offset]) ? $this->contents[$offset] : null;
        }

        public function run() {

            foreach ($this->contents as $key => $content) { // Loop on contents

                if (is_callable($content)) {

                    $content = $this[$key];

                }

                if (is_object($content)) {

                    $reflection = new ReflectionClass($content);

                    if ($reflection->hasMethod('run')) {

                        $content->run();

                    }
                }
            }
        }

        // Set up table to store contact data
        public static function activate() {

            global $wpdb;

            $charset_collate = $wpdb->get_charset_collate();

            $table_name = $wpdb->prefix . CONTACTFORMTABLE;

            if ($wpdb->get_var('SHOW TABLES LIKE \'' . $table_name . '\'') != $table_name) {

                $sql = "CREATE TABLE $table_name (
                    ID mediumint(9) NOT NULL AUTO_INCREMENT,
                    contact_name varchar(255) NOT NULL,
                    contact_email_address varchar(255) NOT NULL,
                    contact_telephone_number varchar(255),
                    contact_enquiry text NOT NULL,
                    contact_page_id bigint(20),
                    contact_submission_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
                    PRIMARY KEY (ID)
                ) $charset_collate;";

                require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

                dbDelta($sql);
            }
        }

        public static function deactivate() {
        }
    }
