<?php
namespace AgileStory\PodcastPublisher;

class Main
{
    public function __construct($plugin_basename)
    {
        date_default_timezone_set(get_option('timezone_string'));

        $this->settings = new Settings();
        $options = $this->settings->options;

        $audio_directory = $options['audio_directory'];
        $audio_link_prefix = $options['audio_link_prefix'];
        $notes_directory = $options['notes_directory'];
        $notes_link_prefix = $options['notes_link_prefix'];
    
        $this->files = new FileManager($audio_directory, $audio_link_prefix, $notes_directory, $notes_link_prefix);
        $this->plugin_basename = $plugin_basename;
        $this->post_builder = new PostBuilder($options['post_type']);

        $podcast_xml_files = explode(',', $options['xml_files']);

        $this->publisher = new Publisher($podcast_xml_files);
        $this->ui = new UploadUI();

        add_action('admin_action_ppPostFile', array($this, 'postFileHandler'));
    }

    public function postFileHandler()
    {
        check_admin_referer('upload-podcast-audio');

        $meta = $this->files->saveFileAndGetMetaData($_POST, $_FILES);

        $post_id = $this->post_builder->createPost($meta);

        //echo  get_edit_post_link($post_id);

        wp_redirect(get_edit_post_link($post_id, ''));
        exit();
    }
}
