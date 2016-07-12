$(document).ready(function() {

  var mediaUploader;

  $('#picOneButton').click(function(e) {
    e.preventDefault();
    if (mediaUploader) {
      mediaUploader.open();
      return;
    }

    mediaUploader = wp.media.frames.file_frame = wp.media({
      title: 'Select Image',
      button: {
        text: 'Select Image',
      },
      multiple: false
    });

    mediaUploader.on('select', function() {
      attachment = mediaUploader.state().get('selection').first().toJSON();
      $('#picture_one_input').val(attachment.url);
    });
  });
});

mediaUploader.open();
