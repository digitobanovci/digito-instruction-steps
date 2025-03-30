jQuery(document).ready(function($) {
    if ( typeof digitoIS !== 'undefined' && digitoIS.steps && digitoIS.steps.length > 0 ) {
        var steps = digitoIS.steps;
        var images = digitoIS.images;
        var currentStep = 0;

        function showStep(index) {
            var step = steps[index];
            var image = images[ getImageIndex( step.orientation ) ];
            $('#digito-is-dialog .digito-is-instructor').attr( 'src', image );
            $('#digito-is-dialog .digito-is-title').text( step.name );
            $('#digito-is-dialog .digito-is-description').text( step.description );
            $('#digito-is-dialog').show();

            var element = $( step.selector );
            if ( element.length ) {
                element.addClass( 'digito-is-blink' );
                setTimeout( function() { element.removeClass( 'digito-is-blink' ); }, 2000 );
            }
        }

        function getImageIndex( orientation ) {
            var orientations = [ 'top-left', 'top-center', 'top-right', 'bottom-left', 'bottom-center', 'bottom-right' ];
            return orientations.indexOf( orientation );
        }

        $('.digito-is-next').on( 'click', function() {
            if ( currentStep < steps.length - 1 ) {
                currentStep++;
                showStep( currentStep );
            }
        });

        $('.digito-is-prev').on( 'click', function() {
            if ( currentStep > 0 ) {
                currentStep--;
                showStep( currentStep );
            }
        });

        $('.digito-is-finish').on( 'click', function() {
            $('#digito-is-dialog').hide();
        });

        showStep( 0 );
    }
});
