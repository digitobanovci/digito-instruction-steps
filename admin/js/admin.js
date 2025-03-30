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
// Dinamičko dodavanje
jQuery(document).ready(function($) {
    var stepIndex = $('#digito-is-steps .digito-is-step').length;

    $('#digito-is-add-step').on('click', function(e) {
        e.preventDefault();
        var newStep = '<div class="digito-is-step">' +
            '<h4>Step ' + (stepIndex + 1) + '</h4>' +
            '<p><label>Step Name</label><br><input type="text" name="digito_is_steps[' + stepIndex + '][name]"></p>' +
            '<p><label>Description</label><br><textarea name="digito_is_steps[' + stepIndex + '][description]"></textarea></p>' +
            '<p><label>Instructor Orientation</label><br>' +
            '<select name="digito_is_steps[' + stepIndex + '][orientation]">' +
            '<option value="top-left">Top Left</option>' +
            '<option value="top-center">Top Center</option>' +
            '<option value="top-right">Top Right</option>' +
            '<option value="bottom-left">Bottom Left</option>' +
            '<option value="bottom-center">Bottom Center</option>' +
            '<option value="bottom-right">Bottom Right</option>' +
            '</select></p>' +
            '<p><label>Element Selector</label><br><input type="text" name="digito_is_steps[' + stepIndex + '][selector]"></p>' +
            '<button class="button digito-is-remove-step">Remove Step</button>' +
            '</div>';
        $('#digito-is-steps').append(newStep);
        stepIndex++;
    });

    $(document).on('click', '.digito-is-remove-step', function(e) {
        e.preventDefault();
        $(this).closest('.digito-is-step').remove();
    });
});
