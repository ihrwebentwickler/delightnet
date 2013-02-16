<script>
    jQuery(document).ready(function () {        
        // future implementation: get google-map-api work on devices
        if (jQuery("body").hasClass("ui-mobile-viewport") === false) {
            jQuery(".contactdata > div:first").show();
            jQuery(".contactform").show();
            
            var mapOptions = {
                zoom: {GOOGLEMAP_ZOOM},
                center: new google.maps.LatLng({GOOGLEMAP_LATLNG}),
                mapTypeId: google.maps.MapTypeId.ROADMAP
            }
        
            var map = new google.maps.Map(document.getElementById('map_canvas1'),mapOptions);
            var image = 'public/images/gmapposition.png';
            var myLatLng = new google.maps.LatLng({GOOGLEMAP_POSITION});
            var posMarker = new google.maps.Marker({
                position: myLatLng,
                map: map,
                icon: image       
            });
        }
        else {
            jQuery("#map_canvas").remove();
        }
 
        // call contact-plugin 
        jQuery('#contact1').contact({
            'obligation': {OBLIGATION},
            'cssError': '{CSS_ERROR}',
            'styleInputFieldError': '{STYLE_INPUT_FIELD_ERROR}',
            'standardError': '{STANDARD_ERROR}',
            'messageStart': '{MESSAGE_START}'
        });
    });
</script>