<?php
namespace AgileStory\PodcastPublisher;

class PodcastXmlUpdaterTest extends \PHPUnit_Framework_TestCase
{
    public function testAddItem()
    {
        $file_path = 'TestData/SamplePodcast.xml';

        $p = new PodcastXmlUpdater($file_path);

        $p->addNewItem($this->getSampleNewItem());

        $this->assertEquals(356, $p->getItemCount());
    }

    public function testLoadFile()
    {
        $file_path = 'TestData/SamplePodcast.xml';

        $p = new PodcastXmlUpdater($file_path);

        $this->assertEquals(355, $p->getItemCount());
    }

    public function testSaveChanges()
    {
        $file_path = 'TestData/SamplePodcast.xml';

        $p = new PodcastXmlUpdater($file_path);

        $p->addNewItem($this->getSampleNewItem());

        $new_file = 'TestData/SamplePodcastWithSavedChangesTest.xml';
        $p->saveChanges($new_file);

        $p2 = new PodcastXmlUpdater($new_file);
        $this->assertEquals(356, $p2->getItemCount());
    }

    private function getSampleNewItem()
    {
        $newItem = new PodcastItem();
        $newItem->title = 'Testing';
        $newItem->description = 'Description';
        $newItem->link = 'http://test.org';
        $newItem->enclosure_url = 'http://test.org/file.mp3';
        $newItem->enclosure_length = 123456;
        $newItem->itunes_duration = '34:56';
        $newItem->itunes_keywords = 'One, Two';
        $newItem->itunes_author = 'Jared Bangs';

        return $newItem;
    }
}
