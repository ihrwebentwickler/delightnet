<div class="mediaGalery" id="mediaGalery{INSTANCEID}">
    <div class="leftfloat">
        <video id="videoPlayer{INSTANCEID}" controls="controls" tabindex="0" fullscreen="false" style="width: {MEDIADIRECTPLAYER_MEDIAWIDTH}px;height:{MEDIADIRECTPLAYER_MEDIAHEIGHT}px;">
        </video>
        <audio id="audioPlayer{INSTANCEID}" controls autobuffer>
        </audio>
        <div id="mediaError{INSTANCEID}" class="video">
            <p>
                Sorry, der Medientitel kann nicht abgespielt werden, entweder ist Ihr Browser zu veraltet oder es konnte nicht fehlerfrei
                ermittelt werden, ob Ihr Browser die Abspielfähigkeit besitzt.
            </p>
        </div>
        <div id="mediaWelcome{INSTANCEID}" class="video" style="background: url('/public/extensions/MediaDirectPlayer/images/{INSTANCEID}/{MEDIADIRECTPLAYER_WELCOMEIMAGE}') no-repeat;">
        </div>
    </div>
    <div id="infobox{INSTANCEID}" class="leftfloat mediaInfobox">
        <div class="mediaTitle">
        </div>
        <div class="mediaDescription">
            Herzlich Willkommen, wählen Sie unten Ihren gewünschten Titel aus.
        </div>
    </div>

    <div class="clear">
    </div>

    <div id="playerGalery{INSTANCEID}" class="MediaDirectPlayer topmargin">
        <div class="wrapper">
            <ul>				
                {MEDIADIRECTPLAYER_IMAGES}
            </ul>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        delightcms.mediadirectplayer.objMedia{INSTANCEID} = {JS_OBJMEDIA};
        
        $('#playerGalery{INSTANCEID}').MediaDirectPlayer({
            autostart: '{MEDIADIRECTPLAYER_AUTOSTART}',
            playerInstance: {INSTANCEID},
            mediaData: delightcms.mediadirectplayer.objMedia{INSTANCEID},
            arrayBrowserData: [global.browser, global.version]
        });
    });
</script>