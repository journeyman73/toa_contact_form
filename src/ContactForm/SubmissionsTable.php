<?php

    if (!class_exists('WP_List_Table')) {
        require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
    }

    class ContactForm_SubmissionsTable extends WP_List_Table
    {
        function __construct() {

            global $status, $page;

            parent::__construct([
                'singular' => __('Submission', 'toa_contact_form'),
                'plural'   => __('Submissions', 'toa_contact_form'),
                'ajax'     => false
            ]);
        }

        /* Text displayed when no data */
        public function no_items() {

            _e('No submissions avaliable.', 'toa_contact_form');
        }

        /* Default column renderer */
        function column_default($item, $column_name) {

            switch ($column_name) {

                case 'contact_name':
                case 'contact_email_address':
                case 'contact_submission_date':
                    return $item[$column_name];

                default:
                    //Show the whole array for troubleshooting purposes
                    return print_r($item, true);
            }
        }

        /* Contact_name column renderer */
        function column_contact_name($item) {

            $actions = [
                'view'   => sprintf('<a href="?page=contact-form&action=view&ID=%s">%s</a>', $item['ID'], __('View', 'toa_contact_form')),
                'delete' => sprintf('<a href="?page=%s&action=delete&ID=%s">%s</a>', $_REQUEST['page'], $item['ID'], __('Delete', 'toa_contact_form')),
            ];

            return sprintf('%s %s', $item['contact_name'], $this->row_actions($actions));
        }

        /* Contact_submission_date column renderer */
        function column_contact_submission_date($item) {

            return date('jS F Y \a\t g:i a', strtotime($item["contact_submission_date"]));
        }


        /* Checkbox column renderer */
        function column_cb($item) {

            return sprintf('<input type="checkbox" name="ID[]" value="%s" />', $item['ID']);
        }

        /* Array of columns on table */
        function get_columns() {

            $columns = [
                'cb'                      => '<input type="checkbox" />',
                'contact_name'            => __('Name', 'toa_contact_form'),
                'contact_email_address'   => __('E-Mail Address', 'toa_contact_form'),
                'contact_submission_date' => __('Date', 'toa_contact_form')
            ];

            return $columns;
        }

        /* Columns that may be used to sort table */
        function get_sortable_columns() {

            $sortable_columns = [
                'contact_name'          => ['contact_name', true],
                'contact_email_address' => ['contact_email_address', false],
            ];

            return $sortable_columns;
        }

        /* Bulk actions for table */
        function get_bulk_actions() {

            $actions = [
                'delete' => 'Delete'
            ];

            return $actions;
        }

        /* Process bulk actions */
        function process_bulk_action() {

            global $wpdb;

            $table_name = $wpdb->prefix . CONTACTFORMTABLE;

            if ('delete' === $this->current_action()) {

                $ids = isset($_REQUEST['ID']) ? $_REQUEST['ID'] : [];

                if (is_array($ids)) {

                    $ids = implode(',', $ids);

                }

                if (!empty($ids)) {

                    $wpdb->query("DELETE FROM $table_name WHERE ID IN($ids)");

                }

            }

        }

        /* Handles data query and filter, sorting, and pagination */
        public function prepare_items() {

            global $wpdb;

            $table_name = $wpdb->prefix . CONTACTFORMTABLE;

            $per_page = 15;

            $columns = $this->get_columns();

            $hidden = [];

            $sortable = $this->get_sortable_columns();

            $this->_column_headers = [$columns, $hidden, $sortable];

            $this->process_bulk_action();

            $total_items = $wpdb->get_var("SELECT COUNT(ID) FROM $table_name");

            $paged = isset($_REQUEST['paged']) ? max(0, intval($_REQUEST['paged']) - 1) : 0;

            $orderby = (isset($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], array_keys($this->get_sortable_columns()))) ? $_REQUEST['orderby'] : 'ID';

            $order = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], ['asc', 'desc'])) ? $_REQUEST['order'] : 'asc';

            $this->items = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name ORDER BY $orderby $order LIMIT %d OFFSET %d", $per_page, $paged), ARRAY_A);

            $this->set_pagination_args([
                'total_items' => $total_items,
                'per_page'    => $per_page,
                'total_pages' => ceil($total_items / $per_page)
            ]);
        }
    }
