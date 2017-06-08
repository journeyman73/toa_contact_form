<?php

    class ContactForm_SubmissionsSettings
    {
        protected $subpage_properties;

        public function __construct($subpage_properties) {

            $this->subpage_properties = $subpage_properties;
        }

        public function run() {

            add_action('admin_menu',[$this, 'add_menu_and_page']);
            add_action('admin_init',[$this, 'register_settings']);
        }

        public function add_menu_and_page() {

            add_submenu_page(
                $this->subpage_properties['parent_slug'],
                $this->subpage_properties['page_title'],
                $this->subpage_properties['menu_title'],
                $this->subpage_properties['capability'],
                $this->subpage_properties['menu_slug'],
                [$this, 'render_subpage']
            );
        }

        public function register_settings() {

            // Register settings section
            register_setting($this->subpage_properties['option_group'], 'contact_form_title', [$this, 'validate_input']);
            register_setting($this->subpage_properties['option_group'], 'contact_form_description', [$this, 'validate_input']);
            register_setting($this->subpage_properties['option_group'], 'contact_form_message', [$this, 'validate_input']);

            // Add settings section
            add_settings_section(
                $this->subpage_properties['option_section'], '', [$this, 'settings_subhead'], $this->subpage_properties['menu_slug']
            );

            // Add settings fields
            add_settings_field(
                $this->subpage_properties['menu_slug'] . '-contact_form_title',
                $this->subpage_properties['option_name']['contact_form_title']['title'],
                [$this, 'settings_field_input_post_plaintext'],
                $this->subpage_properties['menu_slug'],
                $this->subpage_properties['option_section'],
                [
                    'field' => 'contact_form_title',
                    'help'  => $this->subpage_properties['option_name']['contact_form_title']['help']
                ]
            );

            add_settings_field(
                $this->subpage_properties['menu_slug'] . '-contact_form_description',
                $this->subpage_properties['option_name']['contact_form_description']['title'],
                [$this, 'settings_field_input_post_plaintext'],
                $this->subpage_properties['menu_slug'],
                $this->subpage_properties['option_section'],
                [
                    'field' => 'contact_form_description',
                    'help'  => $this->subpage_properties['option_name']['contact_form_description']['help']
                ]
            );

            add_settings_field(
                $this->subpage_properties['menu_slug'] . '-contact_form_message',
                $this->subpage_properties['option_name']['contact_form_message']['title'],
                [$this, 'settings_field_input_post_textarea'],
                $this->subpage_properties['menu_slug'],
                $this->subpage_properties['option_section'],
                [
                    'field' => 'contact_form_message',
                    'help'  => $this->subpage_properties['option_name']['contact_form_message']['help']
                ]
            );
        }

        public function settings_subhead() {

            echo '<p>To add this section to a page use shortcode. <strong>[' . $this->subpage_properties['shortcode_name'] . ']</strong></p>';
        }

        public function settings_field_input_post_plaintext($args) {

            $field = $args['field'];
            $value = get_option($field);

            echo '<p><input id="' . $field . '" type="text" name="' . $field . '" value="' . $value . '" class="regular-text"/></p>';
            echo '<p class="description">' . $args['help'] . '</p>';
        }

        public function settings_field_input_post_textarea($args) {

            $field = $args['field'];
            $value = get_option($field);

            echo '<p><textarea rows="4" id="' . $field . '" name="' . $field . '" class="regular-text"/>' . $value . '</textarea></p>';
            echo '<p class="description">' . $args['help'] . '</p>';
        }

        function validate_input($input) {

            if (is_array($input)) {

                $new_input = serialize($input);

            } else {

                $new_input = sanitize_text_field($input);
            }

            return $new_input;

        }

        public function render_subpage() {
            $option_group = $this->subpage_properties['option_group'];
            $option_section = $this->subpage_properties['option_section'];
            $menu_slug = $this->subpage_properties['menu_slug']
            ?>
            <div class="wrap">
                <h2><?php _e('Contact Form Submissions', 'toa_contact_form')?></h2>
                <?php settings_errors(); ?>
                <form method="post" action="options.php">
                    <?php settings_fields($option_group); ?>
                    <?php do_settings_fields($option_group, $option_section); ?>
                    <?php do_settings_sections($menu_slug); ?>
                    <?php submit_button(); ?>
                </form>
            </div>
            <?php
        }
    }