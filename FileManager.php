<?php
namespace AgileStory\PodcastPublisher;

class FileManager
{
    public function __construct($audio_directory, $audio_link_prefix, $notes_directory, $notes_link_prefix)
    {
        $this->audio_directory = $audio_directory;
        $this->audio_link_prefix = $audio_link_prefix;
        $this->notes_directory = $notes_directory;
        $this->notes_link_prefix = $notes_link_prefix;
    }

    public function getMP3FileName($post_data, $meta)
    {
        $mp3FileName = $meta['date-to-use'].'_';
        $mp3FileName = $mp3FileName.preg_replace('/[^A-Za-z0-9]+/', '', ucwords($meta['title'])).'.mp3';

        return $mp3FileName;
    }

    public function moveAudioFileAndStoreLinkInMeta($post_data, $files, $meta)
    {
        $mp3FileName = $this->getMP3FileName($post_data, $meta);
        $target = $this->audio_directory.$mp3FileName;
        if (move_uploaded_file($files['audio-file']['tmp_name'], $target)) {
            $meta['audio-link'] = $this->audio_link_prefix.$mp3FileName;
        }

        return $meta;
    }

    public function moveNotesFileAndStoreLinkInMeta($post_data, $files, $meta)
    {
        $notesFileName = basename($_FILES['notes']['name']);
        $notesTarget = $this->notes_directory.$notesFileName;
        if (move_uploaded_file($_FILES['notes']['tmp_name'], $notesTarget)) {
            $meta['notes-link'] = $this->notes_link_prefix.$notesFileName;
        }

        return $meta;
    }

    public function saveFileAndGetMetaData($post_data, $files)
    {
        $meta = wp_read_audio_metadata($files['audio-file']['tmp_name']);

        $meta['category'] = $post_data['category'];

        $meta['date-to-use'] = \DateTime::createFromFormat('m-d-Y', $post_data['date-to-use']);
        $meta['date-to-use'] = $meta['date-to-use']->format('m-d-Y');

        $meta = $this->moveAudioFileAndStoreLinkInMeta($post_data, $files, $meta);
        $meta = $this->moveNotesFileAndStoreLinkInMeta($post_data, $files, $meta);

        return $meta;
    }
}
