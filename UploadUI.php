<?php
namespace AgileStory\PodcastPublisher;

class UploadUI
{
    public function __construct()
    {
        add_action('admin_menu', array($this, 'adminMenu'), 10);
    }

    public function adminMenu()
    {
        $render = array($this, 'render');
        add_menu_page(__('Podcast Upload'), __('Upload Podcast'), 'publish_posts', 'upload_podcast', $render, '', 3);
    }

    public function render()
    {
        $this->renderUploadForm();
        $this->renderStyles();
    }

    public function renderStyles()
    {
        ?>
        <style>
        #upload-form label {
            display: block;
            margin-top: 2em;
        }
        </style>
        <?php
    }

    public function renderUploadForm()
    {
        ?>
      <h2>Upload Podcast</h2>
      <form enctype="multipart/form-data" method="post" 
        action="<?php echo admin_url('admin.php'); ?>" class="type-form validate" id="file-form">

       <fieldset id="upload-form">
        <label for="date-to-use">Date Prefix</label>
        <input type="text" name="date-to-use" id="date-to-use" value="<?php echo date('m-d-Y'); ?>" />
        <br />
        <label for="adudio-file">MP3 file with tags set</label>
        <input type="file" name="audio-file" id="audio-file" />
        <br />
        <label for="notes">Notes</label>
        <input type="file" name="notes" id="notes" />
        <br />
        <label for="category">Category</label>
        <?php wp_dropdown_categories(array(
            'hide_empty' => 0, 'name' => 'category', 'order' => 'DESC',
            'selected' => get_option('default_category'))); ?>
        <br />
    <br />
        <input type="submit" id="upload" class="button" value="Upload" />
       </fieldset>

       <div class="clear"></div>
        <?php wp_nonce_field('upload-podcast-audio'); ?>
       <input type="hidden" name="action" value="ppPostFile" />
      </form>
        <?php
    }
}
?>
