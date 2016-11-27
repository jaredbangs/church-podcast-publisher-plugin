<?php
namespace AgileStory\PodcastPublisher;

class PostBuilder
{
    public function __construct($post_type)
    {
        $this->post_type = $post_type;
    }

    public function createPost($meta)
    {
        if (isset($meta['dataformat']) && $meta['dataformat'] == 'mp3') {
            $title = $meta['title'];

            $post_type = $this->post_type;

            $post_date = \DateTime::createFromFormat('m-d-Y', $meta['date-to-use']);

            $post_content = $post_date->format('F j, Y').'<br /><br />';

            if (isset($meta['audio-link'])) {
                $post_content .= 'Download: <span style="text-decoration: underline; color: #c0c0c0;">'.
                 '<a href="'.$meta['audio-link'].'" target="_blank">mp3</a></span>';
            }

            if (isset($meta['audio-link']) && isset($meta['notes-link'])) {
                $post_content .= '<span style="color: #c0c0c0;"><strong> | </strong></span>';
            }

            if (isset($meta['notes-link'])) {
                $post_content .= '<span style="color: #c0c0c0; text-decoration: underline;">'.
                 '<a href="'.$meta['notes-link'].'" target="_blank">notes</a></span>';
            }

            $post_data = array(
             'post_title' => $title,
             'post_type' => $post_type,
             'post_content' => $post_content,
             'post_category' => array($meta['category']),
            );

            $post_id = $this->insertRawHtmlPost($post_data);

            add_post_meta($post_id, 'publish_podcast_trigger', true);

            $this->addPostMetaData($post_id, $meta);

            return $post_id;
        } else {
            return 0;
        }
    }

    private function addPostMetaData($post_id, $meta)
    {
        // Move to custom action in theme for processing meta data
        $this->copyToPostMeta($post_id, $meta, 'artist', 'pastor');
        $this->copyToPostMeta($post_id, $meta, 'audio-link', 'sermon_url');
        $this->copyToPostMeta($post_id, $meta, 'date-to-use', 'sermon_date');

        $this->copyToPostMeta($post_id, $meta, 'artist');
        $this->copyToPostMeta($post_id, $meta, 'audio-link');
        $this->copyToPostMeta($post_id, $meta, 'date-to-use');
        $this->copyToPostMeta($post_id, $meta, 'album');
        $this->copyToPostMeta($post_id, $meta, 'filesize');
        $this->copyToPostMeta($post_id, $meta, 'length');
        $this->copyToPostMeta($post_id, $meta, 'length_formatted');
    }

    private function copyToPostMeta($post_id, $meta, $source_key, $post_meta_key = null)
    {
        if (is_null($post_meta_key)) {
            $post_meta_key = $source_key;
        }

        if (isset($meta[$source_key])) {
            add_post_meta($post_id, $post_meta_key, $meta[$source_key]);
        }
    }

    private function insertRawHtmlPost($post_data)
    {
        remove_filter('content_save_pre', 'wp_filter_post_kses');
        remove_filter('content_filtered_save_pre', 'wp_filter_post_kses');
        $post_id = wp_insert_post($post_data);
        add_filter('content_save_pre', 'wp_filter_post_kses');
        add_filter('content_filtered_save_pre', 'wp_filter_post_kses');

        return $post_id;
    }
}
