<div id="gui">
    <div id="messages">
        <p id="newInstanceMessage">
            Ein neues Feature-Carousel wurde angelegt.
        </p>

        <p id="deleteInstanceMessage">
            Das Feature-Carousel Nr. {MESSAGE_NB_INSTANCE} wurde entfernt.
        </p>

        <p id="newEntryMessage">
            Ein neuer Feature-Carousel wurde erstellt. Bitte bearbeiten Sie jetzt die Daten des neuen Feature-Carousel-Eintrages.
        </p>

        <p id="deleteEntryMessage">
            Der Feature-Carousel-Eintrag Nr. {MESSAGE_NB_CONTENT} wurde entfernt.
        </p>
    </div>
    <div id="instance" class="topmarginSmall">
        <div>
            <p>
                {SELECT_INSTANCES}
                <span id="selectInstance">
                    <span id="newInstance" class="leftmargin">
                        <span>neues Carousel</span>
                        <img src="/cmsadmin/images/listchoise_new.png" alt="" />
                    </span>
                    <span id="deleteInstance">
                        <span>&nbsp;&nbsp;&nbsp;Carousel löschen</span>
                        <img src="/cmsadmin/images/listchoise_delete.png" alt="" />
                    </span>
                </span>

                <span id="selectEntry">
                    <span id="newEntry" class="leftmarginBig">
                        <span>Eintrag neu</span>
                        <img src="/cmsadmin/images/listchoise_new.png" alt="" />
                    </span>

                    <span id="deleteEntry" >
                        <span>&nbsp;&nbsp;&nbsp;Eintrag löschen</span>
                        <img src="/cmsadmin/images/listchoise_delete.png" alt="" />
                    </span>
                </span>
            </p>
        </div>

        <form action="extconf.html" method="post" enctype="multipart/form-data">
            <input type="hidden" name="extSystemName" value="{EXT_SYSTEMNAME}" />
            {PART extinstance}
            <div id="extinstance{INSTANCEID}">    
                {PART FEATURECAROUSEL}
                <div id="FeatureCarousel{INSTANCEKEY}" class="topmarginSmall">
                    <div class="colorbox">
                        <p>
                            <span>
                                &nbsp;Daten für Feature-Carousel
                            </span>
                            <span class="currentInstance">
                                <b>{INSTANCEKEY}</b>
                            </span>
                        </p>

                        <div>
                            <div class="leftfloat textInputLabel">
                                <span>Speicherordner&nbsp;</span>
                            </div>

                            <div class="leftfloat">
                                <input type="text" name="FeatureCarousel[FeatureCarousel][{INSTANCEKEY}][folder]" value="{FOLDER}" />
                            </div>
                        </div>

                        <div class="clear">
                        </div>

                        <div>
                            <div class="leftfloat textInputLabel">
                                <span>Carousel-Speed&nbsp;</span>
                            </div>

                            <div class="leftfloat">
                                <input type="text" name="FeatureCarousel[FeatureCarousel][{INSTANCEKEY}][carouselSpeed]" value="{CAROUSELSPEED}" />
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
                {/PART FEATURECAROUSEL}
                {PART IMAGEPROPERTIES}
                <div class="instanceContent{INSTANCEKEY}">
                    <div class="headerText topmarginSmall" id="imageProperties_{KEY1}">
                        <p>
                            <span class="dynamicarrow">
                                &#9658;
                            </span>
                            <span>
                                <b>FeatureCarousel{KEY1}</b>
                            </span>
                        </p>
                    </div>
                    <div class="colorbox">
                        <div class="leftfloat textInputLabel">
                            <span>Bildname&nbsp;</span>
                        </div>

                        <div class="leftfloat">
                            <input type="text" name="FeatureCarousel[imageProperties][{INSTANCEKEY}][{KEY1}][imageName]" value="{IMAGENAME}" />
                        </div>

                        <div class="clear">
                        </div>

                        <div class="leftfloat textInputLabel topmargin">
                            <span>Bild-Beschreibung&nbsp;</span>
                        </div>

                        <div class="leftfloat">
                            <textarea name="FeatureCarousel[imageProperties][{INSTANCEKEY}][{KEY1}][description]" />{DESCRIPTION}</textarea>
                        </div>

                        <div class="clear">
                        </div>
                    </div>
                </div>
                {/PART IMAGEPROPERTIES}
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
            instanceName: 'FeatureCarousel'
        });
    });
</script>