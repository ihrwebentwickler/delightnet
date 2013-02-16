<div id="gui">
    <div id="messages">
        <p id="newInstanceMessage">
            Ein neuer Mediaplayer wurde angelegt.
        </p>

        <p id="deleteInstanceMessage">
            Der Mediaplayer Nr. {MESSAGE_NB_INSTANCE} wurde entfernt.
        </p>

        <p id="newEntryMessage">
            Ein neuer Galerie-Eintrag wurde erstellt. Bitte bearbeiten Sie jetzt die Daten des neuen Medien-Eintrages.
        </p>

        <p id="deleteEntryMessage">
            Der Media-Eintrag Nr. {MESSAGE_NB_CONTENT} wurde entfernt.
        </p>
    </div>
    <div id="instance" class="topmarginSmall">
        <div>
            <p>
                {SELECT_INSTANCES}
                <span id="selectInstance">
                    <span id="newInstance" class="leftmargin">
                        <span>neuer Player</span>
                        <img src="/cmsadmin/images/listchoise_new.png" alt="" />
                    </span>
                    <span id="deleteInstance">
                        <span>&nbsp;&nbsp;&nbsp;Player löschen</span>
                        <img src="/cmsadmin/images/listchoise_delete.png" alt="" />
                    </span>
                </span>

                <span id="selectEntry">
                    <span id="newEntry" class="leftmarginBig">
                        <span>Media neu</span>
                        <img src="/cmsadmin/images/listchoise_new.png" alt="" />
                    </span>

                    <span id="deleteEntry" >
                        <span>&nbsp;&nbsp;&nbsp;Media löschen</span>
                        <img src="/cmsadmin/images/listchoise_delete.png" alt="" />
                    </span>
                </span>
            </p>
        </div>

        <form action="extconf.html" method="post" enctype="multipart/form-data">
            <input type="hidden" name="extSystemName" value="{EXT_SYSTEMNAME}" />
            {PART extinstance}
            <div id="extinstance{INSTANCEID}">    
                {PART MEDIADIRECTPLAYER}
                <div id="MediaDirectPlayer{INSTANCEKEY}" class="topmarginSmall">
                    <div class="colorbox">
                        <p>
                            <span>
                                &nbsp;Daten für Player
                            </span>
                            <span class="currentInstance">
                                <b>{INSTANCEKEY}</b>
                            </span>
                        </p>

                        <div>
                            <div class="leftfloat textInputLabel">
                                <span>Video-Weite&nbsp;</span>
                            </div>

                            <div class="leftfloat">
                                <input type="text" name="MediaDirectPlayer[MediaDirectPlayer][{INSTANCEKEY}][mediaWidth]" value="{MEDIAWIDTH}" />
                            </div>
                        </div>

                        <div class="clear">
                        </div>

                        <div>
                            <div class="leftfloat textInputLabel">
                                <span>Video-Höhe&nbsp;</span>
                            </div>

                            <div class="leftfloat">
                                <input type="text" name="MediaDirectPlayer[MediaDirectPlayer][{INSTANCEKEY}][mediaHeight]" value="{MEDIAHEIGHT}" />
                            </div>
                        </div>

                        <div class="clear">
                        </div>

                        <div>
                            <div class="leftfloat textInputLabel">
                                <span>Bilder-Ordner&nbsp;</span>
                            </div>

                            <div class="leftfloat">
                                <input type="text" name="MediaDirectPlayer[MediaDirectPlayer][{INSTANCEKEY}][imageFolder]" value="{IMAGEFOLDER}" />
                            </div>
                        </div>

                        <div class="clear">
                        </div>

                        <div>
                            <div class="leftfloat textInputLabel">
                                <span>Auto-Start&nbsp;</span>
                            </div>

                            <div class="leftfloat">
                                <input type="checkbox" name="MediaDirectPlayer[MediaDirectPlayer][{INSTANCEKEY}][autostart]" checked="{AUTOSTART}" />
                            </div>
                        </div>

                        <div class="clear">
                        </div>

                        <div>
                            <div class="leftfloat textInputLabel">
                                <span>Willkommen-Bild&nbsp;</span>
                            </div>

                            <div class="leftfloat">
                                <input type="text" name="MediaDirectPlayer[MediaDirectPlayer][{INSTANCEKEY}][welcomeImage]" value="{WELCOMEIMAGE}" />
                            </div>
                        </div>

                        <div class="clear">
                        </div>
                    </div>

                    <div class="headerLine">
                    </div>

                    <div class="topmarginBig">
                    </div>
                </div>
                {/PART MEDIADIRECTPLAYER}
                {PART GALERY}
                <div class="instanceContent{INSTANCEKEY}">
                    <div class="headerText topmarginSmall" id="galery_{KEY1}">
                        <p>
                            <span class="dynamicarrow">
                                &#9658;
                            </span>
                            <span>
                                <b>Media {KEY1}</b>
                            </span>
                        </p>
                    </div>
                    <div class="colorbox">
                        <div class="leftfloat textInputLabel">
                            <span>Media-Title&nbsp;</span>
                        </div>

                        <div class="leftfloat">
                            <input type="text" name="MediaDirectPlayer[galery][{INSTANCEKEY}][{KEY1}][mediaTitle]" value="{MEDIATITLE}" />
                        </div>

                        <div class="clear">
                        </div>

                        <div class="leftfloat textInputLabel topmargin">
                            <span>Galerie-Bild&nbsp;</span>
                        </div>

                        <div class="leftfloat topmargin">
                            <input type="text" name="MediaDirectPlayer[galery][{INSTANCEKEY}][{KEY1}][galeryImage]" value="{GALERYIMAGE}" />
                        </div>

                        <div class="clear">
                        </div>


                        <div class="leftfloat textInputLabel">
                            <span>Media-Poster&nbsp;</span>
                        </div>

                        <div class="leftfloat">
                            <input type="text" name="MediaDirectPlayer[galery][{INSTANCEKEY}][{KEY1}][mediaPoster]" value="{MEDIAPOSTER}" />
                        </div>

                        <div class="clear">
                        </div>

                        <div class="leftfloat textInputLabel">
                            <span>Media-Beschreibung&nbsp;</span>
                        </div>

                        <div class="leftfloat">
                            <textarea name="MediaDirectPlayer[galery][{INSTANCEKEY}][{KEY1}][mediaDescription]" />{MEDIADESCRIPTION}</textarea>
                        </div>

                        <div class="clear">
                        </div>

                        <div class="leftfloat textInputLabel">
                            <span>Media-Link 1&nbsp;</span>
                        </div>

                        <div class="leftfloat">
                            <input type="text" name="MediaDirectPlayer[galery][{INSTANCEKEY}][{KEY1}][mediaLink1]" value="{MEDIALINK1}" />
                        </div>

                        <div class="clear">
                        </div>

                        <div class="leftfloat textInputLabel">
                            <span>Media-Link 2&nbsp;</span>
                        </div>

                        <div class="leftfloat">
                            <input type="text" name="MediaDirectPlayer[galery][{INSTANCEKEY}][{KEY1}][mediaLink2]" value="{MEDIALINK2}" />
                        </div>


                        <div class="clear">
                        </div>
                    </div>
                </div>
                {/PART GALERY}
            </div>
            {/PART extinstance}
            <div class="headerLine">
            </div>

            <div class="topmarginSmall">
                <input class="button" name="submitExt" type="submit" value="Speichern" />
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {        
        $('#gui').gui({
            instanceName: 'MediaDirectPlayer'
        });
    });
</script>