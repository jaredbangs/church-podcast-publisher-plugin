<?php
namespace AgileStory\PodcastPublisher;

class PodcastItem
{
    public function __construct()
    {
        $this->guid = trim(file_get_contents('/proc/sys/kernel/random/uuid'));
        $this->pub_date = new \DateTime();
    }

    public $description = '';
    public $enclosure_length = 0;
    public $enclosure_type = 'audio/mpeg';
    public $enclosure_url = '';
    public $guid;
    public $itunes_author = '';
    public $itunes_duration = '00:00';
    public $itunes_explicit = 'no';
    public $itunes_keywords = '';
    public $itunes_subtitle; // Default to $title
    public $itunes_summary; // Default to $description
    public $link = '';
    public $pub_date;
    public $title = '';

    public function getNode($xmlDoc)
    {
        $el = $xmlDoc->createElement('item');

        $el->appendChild(new \DOMElement('title', $this->title));
        $el->appendChild(new \DOMElement('description', $this->description));
        $el->appendChild(new \DOMElement('link', $this->link));
        $el->appendChild($this->getEnclosureElement($xmlDoc));
        $el->appendChild($this->getGuidElement($xmlDoc));
        $el->appendChild(new \DOMElement('pubDate', $this->pub_date->format('D, d M Y H:i:s O')));
        $el->appendChild($this->getSubtitleElement($xmlDoc));
        $el->appendChild($this->getSummaryElement($xmlDoc));
        $el->appendChild($xmlDoc->createElement('itunes:duration', $this->itunes_duration));
        $el->appendChild($xmlDoc->createElement('itunes:keywords', $this->itunes_keywords));
        $el->appendChild($xmlDoc->createElement('itunes:author', $this->itunes_author));
        $el->appendChild($xmlDoc->createElement('itunes:explicit', $this->itunes_explicit));

        return $el;
    }

    private function getEnclosureElement($xmlDoc)
    {
        $enclosure = $xmlDoc->createElement('enclosure');
        $enclosure->setAttribute('url', $this->enclosure_url);
        $enclosure->setAttribute('length', $this->enclosure_length);
        $enclosure->setAttribute('type', $this->enclosure_type);

        return $enclosure;
    }

    private function getGuidElement($xmlDoc)
    {
        $guid = $xmlDoc->createElement('guid', $this->guid);
        $guid->setAttribute('isPermaLink', 'false');

        return $guid;
    }

    private function getSubtitleElement($xmlDoc)
    {
        if (strlen($this->itunes_subtitle) > 0) {
            return $xmlDoc->createElement('itunes:subtitle', $this->itunes_subtitle);
        } else {
            return $xmlDoc->createElement('itunes:subtitle', $this->title);
        }
    }

    private function getSummaryElement($xmlDoc)
    {
        if (strlen($this->itunes_summary) > 0) {
            return $xmlDoc->createElement('itunes:summary', $this->itunes_summary);
        } else {
            return $xmlDoc->createElement('itunes:summary', $this->description);
        }
    }
}
