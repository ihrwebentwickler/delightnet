jQuery(document).ready(function () {
    'use strict';
    var mediaplayerVM = mediaplayerVM || {};

    mediaplayerVM = (function(envirement) {
        var objMediaplayer = new Mediaplayer();
        objMediaplayer.init(envirement);
        var intNbMediaGaleries = jQuery('[id*="mediaGalery"]').length;

        function playoutTestData() {
            var objTestData = objMediaplayer.getEnvTestData();
            var strTestFallback = (objTestData.playerCode == -1) ?
            "Kriterien nicht erfüllt: Es wird die Flash-Fallback-Lösung verwendet" : "Kriterien erfüllt: Es wird die HTML5-Umgebung des Browsers verwendet.";
           
            jQuery('#showEnvData').html(
                'Os: ' + objTestData.os + '<br>'
                + 'Browser: ' + objTestData.browser + '<br>'
                + 'Browser-Version: ' + objTestData.browserVersion + '<br>'
                + 'Minimum benötigte Browser-Version für HTML5-Video- und Audio-Ausspielung des ' + objTestData.browser + ': ' + objTestData.browserMinVersion + '<br>'
                + 'Modus Video-Formate: Video1:' + objTestData.video1 + ', Video2:' + objTestData.video2 + '<br>'
                + 'Modus Audio-Formate: Audio1:' + objTestData.audio1 + ', Audio2:' + objTestData.audio2 + '<br>'
                + strTestFallback
                );
        }

        function setMediaIds() {
            for (var i = 1; i <= intNbMediaGaleries; i++){
                if (jQuery('#mediaGalery' + i + ' audio').length > 0) {
                    jQuery('#mediaGalery' + i + ' audio').attr('id','audio' + i + 'Instance');
                }
                if (jQuery('#mediaGalery' + i + ' video').length > 0) {
                    jQuery('#mediaGalery' + i + ' video:first').attr('id','video' + i + 'Instance');
                }
            }   
        }

        function hideMediaElementsByStart() {
            jQuery('.mediaError, .mediaDescription, [class*=mediaLink], video, audio, .flashFallbackPlayer').hide();
        }
        
        function initView() {
            for (var i = 1;   i <= intNbMediaGaleries; i++) {
                jQuery('#mediaGalery' + i + ' .mediaViewWelcome').css('background-image', 'url("images/' + i + '/welcome.jpg")');
                
                jQuery('#mediaGalery' + i + ' .playerData ul li').each(function( index, elem ) {
                    jQuery('#mediaGalery' + i + ' .playerData ul li .sliderimage:eq(' + index + ')').css('background-image', 'url("images/' + i + '/sliderimages/' + (index + 1) + '.jpg")');
                });
            }
        };
        
        function formatView() {
            var intMarginLeftSliderImages = jQuery('#mediaGalery1 .playerData ul li:last').css('margin-left').split('px')[0];
            var intWidthGalery = parseInt(jQuery('#mediaGalery1 .playerData').width());
            var intWidthGaleryElem = parseInt(jQuery('#mediaGalery1 .playerData ul li .sliderimage:last').outerWidth(true));
            var intNbSliderImages = parseInt(intWidthGalery / (intWidthGaleryElem - (2 * intMarginLeftSliderImages)));
            var intWidthSlider = intNbSliderImages * intWidthGaleryElem + intNbSliderImages * intMarginLeftSliderImages * 2;

            jQuery('.playerData').css('width', intWidthSlider);
            jQuery('.playerData').css('margin-left', (intWidthGalery - intWidthSlider) / 2);      
        };
        
        function setActivMediaToPause () { 
            var mediaDom;
            var arrayMediaTypes = ['video', 'audio'];
            var arrayMediaTypesLength = arrayMediaTypes.length;
            
            for (var i = 1; i <= intNbMediaGaleries; i++){
                for (var j = 0;j < arrayMediaTypesLength; j++ ) {
                    mediaDom = document.getElementById(arrayMediaTypes[j] + i + 'Instance');
                    if (!mediaDom.paused) {
                        mediaDom.pause();
                    }
                }
            }
        }
 
        function showMedia(mediaInstance, strMediaType, strMediaTag) {
            var strHideMediaDom = 'audio';
            
            if (strMediaType === "audio") {
                strHideMediaDom = "video";   
            }

            jQuery('#' + strHideMediaDom + mediaInstance + 'Instance').hide();
            jQuery('#mediaGalery' + mediaInstance + ' .mediaViewWelcome').hide();
            jQuery(strMediaTag).show();      
        }
        
        function setActivGaleryItem(mediaInstance, sliderElemNb) {
            jQuery('#mediaGalery' + mediaInstance + ' .playerData ul li .sliderimage').removeClass('sliderClick');   
            jQuery('#mediaGalery' + mediaInstance + ' .playerData ul li:eq(' + sliderElemNb + ') .sliderimage').addClass('sliderClick');    
        }
        
        function getCurrentMediaTag(mediaInstance, strMediaType, intPlayerCode) {
            var strPlayerTag;
            if (intPlayerCode == -1) {
                strPlayerTag = 'flashFallbackPlayer';
            }
            else {
                strPlayerTag = strMediaType;
            }
            
            return '#' + strPlayerTag + mediaInstance + 'Instance';
        }
        
        function  writeMediaDesciptionText(mediaInstance, intClickedElem) {
            var strMediaDesciptionText = jQuery('#mediaGalery' + mediaInstance + ' .playerData ul li:eq(' + intClickedElem + ') .mediaDescription').html();
            jQuery('#mediaGalery' + mediaInstance + ' .mediaDescriptionBig').html(strMediaDesciptionText);
        }

        // play media by click
        jQuery(document).on("click", '.playerData ul li', function(){
            var intClickedElem = jQuery(this).index();
            var hasPlayError = false;
            var mediaInstance = jQuery(this).closest('[id*="media"]').attr('id').split("mediaGalery")[1];
            var strAllMediaLinks = jQuery(this).children('[class*=mediaLink]:first').html();
            var strMediaType = jQuery(this).children('[class*=mediaLink]').attr('class').split("mediaLink")[1].toLowerCase();
            var intPlayerCode = objMediaplayer.getPlayerCode();
            var strFileType = objMediaplayer.getFileType(strMediaType);
            var arrayVideoLinks = strAllMediaLinks.split(";");
            arrayVideoLinks[1] = (arrayVideoLinks[1] && arrayVideoLinks[1] == '*') ? arrayVideoLinks[0] : arrayVideoLinks[1];
  
            setActivGaleryItem(mediaInstance, intClickedElem);
            writeMediaDesciptionText(mediaInstance, intClickedElem);
  
            var strMediaTag = getCurrentMediaTag(mediaInstance, strMediaType, intPlayerCode);
            setActivMediaToPause();
            
            var strVideoLink = arrayVideoLinks[intPlayerCode - 1] + '.' + strFileType;
            jQuery(strMediaTag).html('<source></source>').attr('src', strVideoLink).attr('type', strMediaType + '/' + strFileType);
            
            var mediaDomObj = document.getElementById(strMediaType + mediaInstance + 'Instance');
            hasPlayError = objMediaplayer.playMedia(mediaDomObj);
                
            if (hasPlayError === false) {
                showMedia(mediaInstance, strMediaType, strMediaTag);
            }
        });
        function _init() {
            // if test-output is activated
            if (jQuery('#showEnvData').length > 0 ) {
                playoutTestData();
            }
            
            setMediaIds();
            initView();
            hideMediaElementsByStart();
            formatView();
        }
        
        _init();
    })(envirement);
});