jQuery(document).ready(function($) {
    $('.upload-image-button').on('click', function(e) {
        e.preventDefault();
        var button = $(this);
        var input = button.prev('.instructor-image-url');

        var uploader = wp.media({
            title: 'Select Image',
            button: { text: 'Use this image' },
            multiple: false
        }).on('select', function() {
            var attachment = uploader.state().get('selection').first().toJSON();
            input.val(attachment.url);
        }).open();
    });
});
