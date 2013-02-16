/* 
    Document   : MediaDirectPlayer.js
    Version    : 1.2 (11.12.2012)
    Author     : Gunnar von Spreckelsen <service@ihrwebentwickler.de>
    Description: imageslider for media-selection
 */
;
(function(jQuery) {
    jQuery.fn.MediaDirectPlayer = function (options) { 
        var options = jQuery.extend( {}, jQuery.fn.MediaDirectPlayer.defaults, options);
        
        var images = 3;
        var page = 0;
        
        var wrapper = jQuery('> div', this).css('overflow', 'hidden');
        var slider = wrapper.find('> ul');
        var items = slider.find('> li');
        var single = items.filter(':first');
            
        var singleWidth = single.outerWidth();
        var visible = Math.ceil(wrapper.innerWidth() / singleWidth) - 1; // note: doesn't include padding or border
        var pages = Math.ceil(items.length / visible);
  
        return this.each(function () {
            wrapper.after('<a class="arrow back"></a><a class="arrow forward"></a>');
            
            jQuery('#mediaError' + options.playerInstance).hide();
            jQuery('#videoPlayer' + options.playerInstance).hide();
            jQuery('#audioPlayer' + options.playerInstance).hide();
            jQuery('#mediaWelcome' + options.playerInstance).show();
 
            var NbPlayers = jQuery('video[id*="videoPlayer"]').length;
 
            if (jQuery('#mediaHeaderNavigation').length != 0) {
                for (var i = 1; i <= NbPlayers;  i++) {
                    jQuery('#mediaGalery' + i).hide();
                }
            }
            
            if (options.autostart == 'on') {
                jQuery('#playerGalery' + options.playerInstance + ' ul li.1 .sliderimage').addClass('MediaDirectPlayerClick');
                setMediaInfoBox(1);
                playMedia(1, getPlayerCodec());
            }
  
            gotoPage("next");
  
            // 1. Hover-Effect by mouseouver of sliderimage-item     
            jQuery('#playerGalery' + options.playerInstance + ' ul li .sliderimage').on({
                hover : function() {
                    jQuery.data(document.body, 'parentElement', jQuery(this).parent());
                    jQuery(this).find('.sliderimageTitle').addClass('darkBackground');
                    jQuery(this).css('cursor', 'pointer');
                },
                mouseleave : function() {
                    jQuery(this).find('.sliderimageTitle').removeClass('darkBackground');
                },
                click : function() {
                    jQuery('#playerGalery' + options.playerInstance + ' ul li .sliderimage').removeClass('MediaDirectPlayerClick');
                    jQuery('#playerGalery' + options.playerInstance + ' .play').show();                   
                    jQuery(this).addClass('MediaDirectPlayerClick');
                    
                    var arrayMediaString = jQuery(jQuery.data(document.body, 'parentElement')).attr('class').split(" ");
                    var intMediaNb =  arrayMediaString[0].split("media")[1];
                    setMediaInfoBox(intMediaNb);
                    playMedia(intMediaNb, getPlayerCodec());
                }
            });
            
            // 2. Bind to the forward and back buttons
            jQuery('#playerGalery' + options.playerInstance + ' .forward').click(function () {
                gotoPage("next");
            });
            jQuery('#playerGalery' + options.playerInstance + ' .back').click(function () {
                gotoPage("last");                
            });
        });
    
        // 2. paging function
        function gotoPage(pageClick) {           
            page = (pageClick == "next") ? page + 1 : page - 1;
            page = (page < 1) ? 1 : page;
            page = (page > pages) ? pages : page;

            var leftposition = (images * singleWidth) * (page - 1);

            if (page == pages && items.size() > images) {
                jQuery('#playerGalery' + options.playerInstance + ' .forward').hide(300)
            }
            else {
                jQuery('#playerGalery' + options.playerInstance + ' .forward').show();
            }

            if (page == 1 && items.size() > images) {
                jQuery('#playerGalery' + options.playerInstance + ' .back').hide(300);
            }
            else {
                jQuery('#playerGalery' + options.playerInstance + ' .back').show();
            }
                
            if (items.size() <= images) {
                jQuery('#playerGalery' + options.playerInstance + ' .forward').hide()
                jQuery('#playerGalery' + options.playerInstance + ' .back').hide();   
            }
                
            wrapper.animate({
                "scrollLeft": leftposition
            }, 700, function () {
                wrapper.scrollLeft(leftposition);
            });
        }
    
        // 3. set video- and- audio-html5-instances to pause
        function setVideoAndAudioToPause(html5Tag, currentMediaTagId) {
            var mediaPlayerDomInstances = document.getElementsByTagName(html5Tag);
                 
            for(var i = 0; i < mediaPlayerDomInstances.length; i++){     
                if (!mediaPlayerDomInstances[i].paused && jQuery(mediaPlayerDomInstances[i]).attr('id') != currentMediaTagId) {
                    mediaPlayerDomInstances[i].pause();
                }
            }
        }
            
        // 4. stop playing of all video- or audio-instances by dom-cklick    
        jQuery('audio, video').click(function() {
            setVideoAndAudioToPause("video", this.id);
            setVideoAndAudioToPause("audio", this.id);
        });
            
        // 5. media-navigation  
        jQuery('#mediaHeaderNavigation div ul li').click(function() {
            var indexOfGalery = jQuery(this).index() + 1;
            var NbPlayers = jQuery('video[id*="videoPlayer"]').length;
        
            setVideoAndAudioToPause("video", "");
            setVideoAndAudioToPause("audio", "");
 
            for (var i = 1; i <= NbPlayers; i++) {
                jQuery('#mediaGalery' + i).hide();
            }

            jQuery('#mediaGalery' + indexOfGalery).show(200);
        });
        
        // 6. show videobox-info
        function setMediaInfoBox(medianb) {                           
            jQuery('#infobox' + options.playerInstance + ' .mediaTitle').html(options.mediaData["media" + medianb]["mediaTitle"]);
            jQuery('#infobox' + options.playerInstance + ' .mediaDescription').html(options.mediaData["media" + medianb]["mediaDescription"]);
        }
    
        // 7. get Player-Code (Browser-Detection)
        function getPlayerCodec() {
            /*
                 * 
                 * the current orgin server-env recognizes and submits following browsers:
                 * 
                 *  'Firefox'    =>'firefox' >= 3.5 [PLAYERCODE 1]
                 *  'Opera'      =>'opera' >= 10.5 [PLAYERCODE 1]
                 * 
                 * 	'MSIE'       =>'ie' >= 9 [PLAYERCODE 2]
                    'Chrome'     =>'chrome' >= 3 [PLAYERCODE 2]
                    'OmniWeb'    =>'omniweb' >= 3 [PLAYERCODE 2]
                    'Safari'     =>'safari' >= 3 [PLAYERCODE 2]
                    'Opera Mobi' =>'opera-mobile' >= 11.1 [PLAYERCODE 2]
                    'IEMobile'   =>'ie-mobile' >= 9 [PLAYERCODE 2]
                    'BlackBerry'     =>'blackberry' >= 6 [PLAYERCODE 2]
                    'Konqueror'  =>'konqueror' >= 3 [PLAYERCODE 2]
                 * 
                 *  flash-fallback currently plays mp4
                 *  
                 *  
                 *  Audio:
                 *  http://demosthenes.info/blog/261/HTML5-Audio-Video-Codec-Support-Production-Workflow
                 *  
                 *  
                 *  KEYS:
                 *  
                 *  Video-Links
                 *  1: Ogv
                 *  2: Mp4
                 *  
                 *  Audio-Keys
                 *  1: Ogg
                 *  2: Mp3
                 */         
                
            var intFileLinkKey = -1;
                
            var objBrowserCodec = [
            {
                agent: "firefox",
                minVersion: 3.5,
                fileLinkKey: 1
            },
            {
                agent: "opera",
                minVersion: 10.5,
                fileLinkKey: 1
            },
            {
                agent: "ie",
                minVersion: 9,
                fileLinkKey: 2
            },
            {
                agent: "chrome",
                minVersion: 3,
                fileLinkKey: 2
            },
            {
                agent: "omniweb",
                minVersion: 3,
                fileLinkKey: 2
            },
            {
                agent: "safari",
                minVersion: 3,
                fileLinkKey: 2
            },
            {
                agent: "opera-mobile",
                minVersion: 11.1,
                fileLinkKey: 2
            },
            {
                agent: "ie-mobile",
                minVersion: 9,
                fileLinkKey: 2
            },
            {
                agent: "blackberry",
                minVersion: 6,
                fileLinkKey: 2
            },
            {
                agent: "konqueror",
                minVersion: 3,
                fileLinkKey: 2
            }
            ];
                
            for(var i = 0; i < objBrowserCodec.length; i += 1) {
                if  (   (objBrowserCodec[i].agent === options.arrayBrowserData[0] && options.arrayBrowserData[1] >= objBrowserCodec[i].minVersion)
                    ||  (objBrowserCodec[i].agent === options.arrayBrowserData[0] && objBrowserCodec[i].minVersion === 0)
                    )
                    {
                    intFileLinkKey = objBrowserCodec[i].fileLinkKey;
                    break;
                }
            }
            
            return intFileLinkKey;
        }
        
        // 8. set mediaData and play
        function playMedia(intMediaNb, intFileLinkKey) {
            var isVideoLink;
            var objRegExpVideoLink = /ogv|mp4/gi;
            var strMediaTag;
                
            jQuery('#mediaError' + options.playerInstance).hide();
            jQuery('#videoPlayer' + options.playerInstance).hide();
            jQuery('#audioPlayer' + options.playerInstance).hide();
            jQuery('#mediaWelcome' + options.playerInstance).show();
                            
            if (intFileLinkKey != -1) {      
                isVideoLink = options.mediaData["media" + intMediaNb]["mediaLink" + intFileLinkKey].search(objRegExpVideoLink);
                jQuery('#mediaWelcome' + options.playerInstance).hide();
                   
                // true === video-link
                if(isVideoLink != -1) {
                    strMediaTag = 'video';
                }
                else {
                    strMediaTag = 'audio';
                }
                
                setVideoAndAudioToPause("video", strMediaTag + 'Player' + options.playerInstance);
                setVideoAndAudioToPause("audio", strMediaTag + 'Player' + options.playerInstance);
                
                jQuery('#' + strMediaTag + 'Player' + options.playerInstance).show();
                jQuery('#' + strMediaTag + 'Player' + options.playerInstance).attr("poster", options.mediaData["media" + intMediaNb].mediaPoster);
                    
                if (jQuery('#' + strMediaTag + 'Player'  + options.playerInstance + ' source').length == 0) {
                    jQuery('#' + strMediaTag + 'Player'  + options.playerInstance).wrapInner('<source />');
                }
                    
                jQuery('#' + strMediaTag + 'Player'  + options.playerInstance + ' source').attr('src',options.mediaData["media" + intMediaNb]["mediaLink" + intFileLinkKey]);
                var jsDomMediaObj = document.getElementById(strMediaTag + 'Player' + options.playerInstance);
                jsDomMediaObj.load();
                jsDomMediaObj.play();    
            }
            else {
                jQuery('#mediaError' + options.playerInstance).show();
            }
        }
            
        // default configuration properties
        jQuery.fn.MediaDirectPlayer.defaults = {			
            autostart: 1,
            playerInstance: 1,
            mediaData: 'null',
            arrayBrowserData: null
        }; 
	
    /*
    * properties of mediaData:
    * str linkDomain
    * str mediaTitle
    * str mediaPoster
    * str mediaDescription
    *
    *
    */
    };
})(jQuery, jQuery);