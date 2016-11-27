<?php
namespace AgileStory\PodcastPublisher;

class PodcastItemTest extends \PHPUnit_Framework_TestCase
{
    public function testDescription()
    {
        $this->assertEquals(
            'Description',
            $this->addNewItem()->getElementsByTagName('description')->item(0)->nodeValue
        );
    }

    public function testEnclosure()
    {
        $enc = $this->addNewItem()->getElementsByTagName('enclosure')->item(0);

        $this->assertEquals('http://test.org/file.mp3', $enc->getAttribute('url'));
        $this->assertEquals('123456', $enc->getAttribute('length'));
        $this->assertEquals('audio/mpeg', $enc->getAttribute('type'));
    }

    public function testGuid()
    {
        $guid = $this->addNewItem()->getElementsByTagName('guid')->item(0);

        $this->assertEquals('false', $guid->getAttribute('isPermaLink'));
        $this->assertGreaterThan(0, strlen($guid->nodeValue));
    }

    public function testITunesAuthor()
    {
        $this->assertEquals(
            'Jared Bangs',
            $this->addNewItem()->getElementsByTagName('itunes:author')->item(0)->nodeValue
        );
    }

    public function testITunesDuration()
    {
        $this->assertEquals('34:56', $this->addNewItem()->getElementsByTagName('itunes:duration')->item(0)->nodeValue);
    }

    public function testITunesExplicitDefaultsToNo()
    {
        $this->assertEquals('no', $this->addNewItem()->getElementsByTagName('itunes:explicit')->item(0)->nodeValue);
    }

    public function testITunesKeywords()
    {
        $this->assertEquals(
            'One, Two',
            $this->addNewItem()->getElementsByTagName('itunes:keywords')->item(0)->nodeValue
        );
    }

    public function testITunesSubtitleDefaultsToTitle()
    {
        $this->assertEquals(
            'Testing',
            $this->addNewItem()->getElementsByTagName('itunes:subtitle')->item(0)->nodeValue
        );
    }

    public function testITunesSummaryDefaultsToDescription()
    {
        $this->assertEquals(
            'Description',
            $this->addNewItem()->getElementsByTagName('itunes:summary')->item(0)->nodeValue
        );
    }

    public function testLink()
    {
        $this->assertEquals('http://test.org', $this->addNewItem()->getElementsByTagName('link')->item(0)->nodeValue);
    }

    public function testPubDate()
    {
        $file_path = 'TestData/SamplePodcast.xml';

        $p = new PodcastXmlUpdater($file_path);

        $item = $this->getSampleNewItem();

        $now = new \DateTime();
        $item->pub_date = $now;

        $p->addNewItem($item);

        $expected = $now->format('D, d M Y H:i:s O');

        $this->assertEquals($expected, $this->addNewItem()->getElementsByTagName('pubDate')->item(0)->nodeValue);
    }

    public function testTitle()
    {
        $this->assertEquals('Testing', $this->addNewItem()->getElementsByTagName('title')->item(0)->nodeValue);
    }

    private function addNewItem()
    {
        $file_path = 'TestData/SamplePodcast.xml';

        $p = new PodcastXmlUpdater($file_path);

        $p->addNewItem($this->getSampleNewItem());

        $channel = $p->xml->getElementsByTagName('channel')->item(0);

        $first_item = $channel->getElementsByTagName('item')->item(0);

        return $first_item;
    }

    private function getSampleNewItem()
    {
        $new_item = new PodcastItem();
        $new_item->title = 'Testing';
        $new_item->description = 'Description';
        $new_item->link = 'http://test.org';
        $new_item->enclosure_url = 'http://test.org/file.mp3';
        $new_item->enclosure_length = 123456;
        $new_item->itunes_duration = '34:56';
        $new_item->itunes_keywords = 'One, Two';
        $new_item->itunes_author = 'Jared Bangs';

        return $new_item;
    }
}
