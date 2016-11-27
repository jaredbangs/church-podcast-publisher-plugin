<?php
namespace AgileStory\PodcastPublisher;

class Publisher
{
    public function __construct($xml_file_paths_array)
    {
        $this->xml_file_path_array = $xml_file_paths_array;

        add_action('transition_post_status', array($this, 'onPostStatusTransition'), 10, 3);
    }

    public function onPostStatusTransition($new_status, $old_status, $post)
    {
        if ('publish' == $new_status && $this->isWaitingToBePublished($post)) {
            $this->publishXmlOnPostPublish($post);
        }
    }

    public function publishXmlOnPostPublish($post)
    {
        $item = $this->getNewItem($post);

        foreach ($this->xml_file_path_array as $xml_file_path) {
            $p = new PodcastXmlUpdater($xml_file_path);
            $p->addNewItem($item);
            $p->saveChanges($xml_file_path);
        }
    
        delete_post_meta($post->ID, 'publish_podcast_trigger');
    }

    private function getNewItem($post)
    {
        $post_date = \DateTime::createFromFormat('m-d-Y', get_post_meta($post->ID, 'date-to-use', true));

        $artist = get_post_meta($post->ID, 'artist', true);
    
        $description = $post_date->format('m/d/Y: ') . $post->post_title . ' by ' . $artist;

        $item = new PodcastItem();
        $item->title = $post->post_title;
        $item->description = $description;
        $item->link = site_url();
        $item->enclosure_url = get_post_meta($post->ID, 'audio-link', true);
        $item->enclosure_length = get_post_meta($post->ID, 'filesize', true);
        $item->itunes_duration = get_post_meta($post->ID, 'length_formatted', true);
        $item->itunes_keywords = $post->post_title . ', ' . $artist;
        $item->itunes_author = $artist;

        return $item;
    }

    private function isWaitingToBePublished($post)
    {
        $flag = get_post_meta($post->ID, 'publish_podcast_trigger', true);
        return !empty($flag) && $flag == true;
    }
}
