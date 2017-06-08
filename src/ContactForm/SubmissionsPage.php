<?php

class ContactForm_SubmissionsPage
{

    protected $page_properties;

    public function __construct($page_properties) {

        $this->page_properties = $page_properties;
    }

    public function run() {

        add_action('admin_menu', [$this, 'add_menu_and_page']);
    }

    public function add_menu_and_page() {

        add_menu_page(
            $this->page_properties['page_title'],
            $this->page_properties['menu_title_main'],
            $this->page_properties['capability'],
            $this->page_properties['menu_slug'],
            [$this, 'render_page'],
            $this->page_properties['menu_icon'],
            $this->page_properties['menu_position']
        );

        add_submenu_page(
            $this->page_properties['menu_slug'],
            $this->page_properties['page_title'],
            $this->page_properties['menu_title_sub'],
            $this->page_properties['capability'],
            $this->page_properties['menu_slug'],
            [$this, 'render_page']
        );

    }

    /** Handles the rendering of listing page */
    public function render_page() {

        global $wpdb;

        $table = new ContactForm_SubmissionsTable();
        $table->prepare_items();

        $message = '';

        if ('view' === $table->current_action()) {

            if (isset($_REQUEST['ID'])) {

                $table_name = $wpdb->prefix . CONTACTFORMTABLE;

                $item = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE ID = %d", $_REQUEST['ID']), ARRAY_A);

                if (!$item) {

                    $message = '<div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible"><p><strong>' . __('Item not found', 'toa_contact_form') . '</strong></p></div>';

                }
            }

            include(plugin_dir_path(dirname(__FILE__)) . 'views/submissionEntryView.php');

        } else {

            if ('delete' === $table->current_action()) {

                $message = '<div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible"><p><strong>' . sprintf(__('Items deleted: %d', 'toa_contact_form'), count($_REQUEST['ID'])) . '</strong></p></div>';

            }

            include(plugin_dir_path(dirname(__FILE__)) . 'views/submissionTableView.php');
        }
    }
}
