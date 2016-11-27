<?php
namespace AgileStory\PodcastPublisher;

class Settings
{
    public function __construct()
    {
        $this->options_key = 'podcast_publisher';
        $this->options = get_option($this->options_key);
        $this->setDefaults();
        $this->option_group = $this->options_key . '_option_group';
        $this->page_slug = 'podcast-upload-settings';
        add_action('admin_menu', array( $this, 'addSettingsPage' ));
        add_action('admin_init', array( $this, 'initializeSettings' ));
    }

    public function addSettingsPage()
    {
        add_options_page(
            'Podcast Upload Settings',
            'Podcast Uploading',
            'manage_options',
            $this->page_slug,
            array( $this, 'renderSettingsForm' )
        );
    }

    public function initializeSettings()
    {
        add_settings_section('default', 'Podcast Publisher Settings', null, $this->page_slug);

        register_setting($this->option_group, $this->options_key, array( $this, 'sanitize'));

        add_settings_field('post_type', 'Post Type', array( $this, 'postTypeField' ), $this->page_slug);
    
        $this->addSettingsTextField('xml_files', 'Xml Files');
        $this->addSettingsTextField('audio_directory', 'Audio Directory');
        $this->addSettingsTextField('audio_link_prefix', 'Audio Link Prefix');
        $this->addSettingsTextField('notes_directory', 'Notes Directory');
        $this->addSettingsTextField('notes_link_prefix', 'Notes Link Prefix');
    }

    public function postTypeField()
    {
        $html = '<select id="post_type" name="' . $this->options_key . '[post_type]">';
        foreach (get_post_types('', 'names') as $post_type) {
            if ($post_type == $this->options['post_type']) {
                $html .= '<option selected="selected" value="' . $post_type . '">' . $post_type . '</option>';
            } else {
                $html .= '<option value="' . $post_type . '">' . $post_type . '</option>';
            }
        }
        $html .= '</select>';

        echo $html;
    }

    public function renderSettingsForm()
    {
        ?>
    <div class="settings">
        <form method="post" action="options.php">
        <?php
        settings_fields($this->option_group);
        do_settings_sections($this->page_slug);
        submit_button();
        ?>
        </form>
    </div>
        <?php
    }

    public function sanitize($input)
    {
        $sanitized_input = array();

        $this->sanitizeTextField($input, $sanitized_input, 'post_type');
        $this->sanitizeTextField($input, $sanitized_input, 'xml_files');
        $this->sanitizeTextField($input, $sanitized_input, 'audio_directory');
        $this->sanitizeTextField($input, $sanitized_input, 'audio_link_prefix');
        $this->sanitizeTextField($input, $sanitized_input, 'notes_directory');
        $this->sanitizeTextField($input, $sanitized_input, 'notes_link_prefix');

        return $sanitized_input;
    }
    
    public function textField($key)
    {
        printf(
            '<input type="text" id="' . $key . '" name="' . $this->options_key . '[' . $key . ']" value="%s" />',
            isset($this->options[$key]) ? esc_attr($this->options[$key]) : ''
        );
    }

    private function addSettingsTextField($key, $label)
    {
        add_settings_field($key, $label, function () use ($key) {
            return $this->textField($key);
        }, $this->page_slug);
    }

    private function sanitizeTextField(&$unsanitized, &$sanitized, $key)
    {
        if (isset($unsanitized[$key])) {
            $sanitized[$key] = sanitize_text_field($unsanitized[$key]);
        }
    }

    private function setDefault($key, $value)
    {
        if (!isset($this->options[$key])) {
            $this->options[$key] = $value;
        }
    }

    private function setDefaults()
    {
        $this->setDefault('post_type', 'post');
        $this->setDefault('xml_files', ABSPATH . 'MyPodcast.xml');
        $this->setDefault('audio_directory', ABSPATH . 'audio/');
        $this->setDefault('audio_link_prefix', get_site_url() . '/audio/');
        $this->setDefault('notes_directory', ABSPATH . 'notes/');
        $this->setDefault('notes_link_prefix', get_site_url() . '/notes/');
    }
}
