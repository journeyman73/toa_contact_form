<?php
    //if uninstall not called from WordPress exit
    if (!defined('WP_UNINSTALL_PLUGIN')) {
        exit();
    }

    //remove options
    delete_site_option('contact_form_title');
    delete_site_option('contact_form_description');
    delete_site_option('contact_form_message');

    //drop database table
    global $wpdb;

    $table_name = $wpdb->prefix . 'toa_contact_form';

    $wpdb->query("DROP TABLE IF EXISTS {$table_name}");
