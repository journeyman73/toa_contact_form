<?php
    /*
    Plugin Name: TOA Contact Form
    Plugin URI: https://github.com/journeyman73/toa_contact_form
    Description: Contact Form - Test
    Author: Kevin Relland
    Version: 1.0.0

    License: GPL2
    */
    /*
    Copyright 2017  Kevin Relland  (email : kevin.relland@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty ofShortcode.php
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
    */

    spl_autoload_register('toa_contact_form_autoload_function');

    function toa_contact_form_autoload_function($class_name) {

        if (false !== strpos($class_name, 'ContactForm')) {

            $classes_dir = realpath(plugin_dir_path(__FILE__)) . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR;

            $class_file = str_replace('_', DIRECTORY_SEPARATOR, $class_name) . '.php';

            require_once $classes_dir . $class_file;

        }
    }

    add_action('plugins_loaded', 'toa_contact_form_init'); // Hook initialization function

    function toa_contact_form_init() {

        $plugin = new ContactForm_Main();
        $plugin['path'] = realpath(plugin_dir_path(__FILE__)) . DIRECTORY_SEPARATOR;
        $plugin['url'] = plugin_dir_url(__FILE__);
        $plugin['version'] = '1.0.0';
        $plugin['menu_slug'] = 'contact-form';

        $plugin['contact_submissions_page_properties'] = [
            'page_title'      => 'Contact Form Submissions',
            'menu_title_main' => 'Contact Form',
            'menu_title_sub'  => 'Submissions',
            'capability'      => 'edit_pages',
            'menu_slug'       => $plugin['menu_slug'],
            'menu_icon'       => 'dashicons-email',
            'menu_position'   => 9,
            'shortcode_name'  => $plugin['menu_slug']
        ];

        $plugin['csv_download_properties'] = [
            'parent_slug'    => $plugin['menu_slug'],
            'page_title'     => 'Contact Form Submissions Download',
            'menu_title'     => 'Download',
            'capability'     => 'edit_pages',
            'menu_slug'      => 'contact-form-download',
            'shortcode_name' => $plugin['menu_slug']
        ];

        $plugin['contact_submissions_shortcode_properties'] = [
            'shortcode_name' => $plugin['menu_slug']
        ];

        $plugin['contact_submissions_settings_properties'] = [
            'parent_slug'    => $plugin['menu_slug'],
            'page_title'     => 'Contact Form Submissions Settings',
            'menu_title'     => 'Settings',
            'capability'     => 'edit_posts',
            'menu_slug'      => 'contact-form-settings',
            'shortcode_name' => $plugin['menu_slug'],
            'option_group'   => 'contact_form_settings_group',
            'option_section' => 'contact_form_settings_section',
            'option_name'    => [
                'contact_form_title'       => [
                    'title' => 'Contact Form Title',
                    'help'  => 'Title if required for the form',
                    'input' => 'post_plaintext'
                ],
                'contact_form_description' => [
                    'title' => 'Contact Form Description',
                    'help'  => 'Intro sub text if required for the form'
                ],
                'contact_form_message'     => [
                    'title' => 'Success Message',
                    'help'  => 'The message shown on successful submission of the form'
                ],
                'contact_form_recaptcha_site_key'     => [
                    'title' => 'reCAPTCHA Site Key',
                    'help'  => 'Go to Google\'s <a href="https://www.google.com/recaptcha/admin">reCAPTCHA admin page</a>. Register your site. Get a site key and secret key.'
                ],
                'contact_form_recaptcha_secret_key'     => [
                    'title' => 'reCAPTCHA Secret Key',
                    'help'  => ''
                ]
            ]
        ];

        $plugin['contact_submissions'] = 'toa_contact_submissions_page';

        $plugin['csv_download'] = 'toa_contact_submissions_csv_download';

        $plugin['shortcode'] = 'toa_contact_submissions_shortcode';

        $plugin['settings_page'] = 'toa_contact_submissions_settings';

        $plugin->run();
    }

    function toa_contact_submissions_page($plugin) {

        static $object;

        if (null !== $object) {

            return $object;

        }

        $object = new ContactForm_SubmissionsPage($plugin['contact_submissions_page_properties']);

        return $object;
    }

    function toa_contact_submissions_csv_download($plugin) {

        static $object;

        if (null !== $object) {

            return $object;

        }

        $object = new ContactForm_SubmissionsDownload($plugin['csv_download_properties']);

        return $object;
    }

    function toa_contact_submissions_shortcode($plugin) {

        static $object;

        if (null !== $object) {
            return $object;
        }

        $object = new ContactForm_SubmissionsShortcode($plugin['contact_submissions_shortcode_properties']);

        return $object;
    }

    function toa_contact_submissions_settings($plugin) {

        static $object;

        if (null !== $object) {
            return $object;
        }

        $object = new ContactForm_SubmissionsSettings($plugin['contact_submissions_settings_properties']);

        return $object;
    }

    register_activation_hook(__FILE__, ['ContactForm_Main', 'activate']);

    register_deactivation_hook(__FILE__, ['ContactForm_Main', 'deactivate']);
