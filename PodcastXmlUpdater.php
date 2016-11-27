<?php
namespace AgileStory\PodcastPublisher;

class PodcastXmlUpdater
{
    public function __construct($file_path)
    {
        $this->file_path = $file_path;

        $this->xml = new \DOMDocument();
        $this->xml->preserveWhiteSpace = false;
        $this->xml->formatOutput = true;
        $this->xml->load($this->file_path);
    }

    public function addNewItem($podcast_item)
    {
        $channel = $this->xml->getElementsByTagName('channel')->item(0);

        $first_item = $channel->getElementsByTagName('item')->item(0);

        $channel->insertBefore($podcast_item->getNode($this->xml), $first_item);
    }

    public function getItemCount()
    {
        $channel = $this->xml->getElementsByTagName('channel')->item(0);

        return $channel->getElementsByTagName('item')->length;
    }

    public function saveChanges($file_path = null)
    {
        if (null === $file_path) {
            $file_path = $this->file_path;
        }

        $this->xml->save($file_path);
    }
}
