<div id="gui">
    <div id="messages">
        <p id="newInstanceMessage">
            Eine neue Blogger-Instance wurde angelegt.
        </p>

        <p id="deleteInstanceMessage">
            Der Blog-Eintrag Nr. {MESSAGE_NB_INSTANCE} wurde entfernt.
        </p>

        <p id="newEntryMessage">
            Ein neuer Blog-Eintrag wurde erstellt. Bitte bearbeiten Sie jetzt die Daten des neuen Blog-Eintrages.
        </p>

        <p id="deleteEntryMessage">
            Der Blog-Eintrag Nr. {MESSAGE_NB_CONTENT} wurde entfernt.
        </p>
    </div>
    <div id="instance" class="topmarginSmall">
        <div>
            <p>
                {SELECT_INSTANCES}
                <span id="selectInstance">
                    <span id="newInstance" class="leftmargin">
                        <span>neuer Blog</span>
                        <img src="/cmsadmin/images/listchoise_new.png" alt="" />
                    </span>
                    <span id="deleteInstance">
                        <span>&nbsp;&nbsp;&nbsp;Blog löschen</span>
                        <img src="/cmsadmin/images/listchoise_delete.png" alt="" />
                    </span>
                </span>

                <span id="selectEntry">
                    <span id="newEntry" class="leftmarginBig">
                        <span>Blog-Eintrag neu</span>
                        <img src="/cmsadmin/images/listchoise_new.png" alt="" />
                    </span>

                    <span id="deleteEntry" >
                        <span>&nbsp;&nbsp;&nbsp;Blog-Eintrag löschen</span>
                        <img src="/cmsadmin/images/listchoise_delete.png" alt="" />
                    </span>
                </span>
            </p>
        </div>

        <form action="extconf.html" method="post" enctype="multipart/form-data">
            <input type="hidden" name="extSystemName" value="{EXT_SYSTEMNAME}" />
            {PART extinstance}
            <div id="extinstance{INSTANCEID}">    
                {PART BLOGGER}
                <div id="Blogger{INSTANCEKEY}" class="topmarginSmall">
                    <div class="colorbox">
                        <p>
                            <span>
                                &nbsp;Daten für Blog
                            </span>
                            <span class="currentInstance">
                                <b>{INSTANCEKEY}</b>
                            </span>
                        </p>

                        <div>
                            <div class="leftfloat textInputLabel">
                                <span>Blog-Titel&nbsp;</span>
                            </div>

                            <div class="leftfloat">
                                <input type="text" name="Blogger[Blogger][{INSTANCEKEY}][blogtitle]" value="{BLOGTITLE}" />
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
                {/PART BLOGGER}
                {PART BLOGENTRIES}
                <div class="instanceContent{INSTANCEKEY}">
                    <div class="headerText topmarginSmall" id="imageProperties_{KEY1}">
                        <p>
                            <span class="dynamicarrow">
                                &#9658;
                            </span>
                            <span>
                                <b>Blogger{KEY1}</b>
                            </span>
                        </p>
                    </div>
                    <div class="colorbox">
                        <div class="leftfloat textInputLabel">
                            <span>Überschrift&nbsp;</span>
                        </div>

                        <div class="leftfloat">
                            <input type="text" name="Blogger[blogEntries][{INSTANCEKEY}][{KEY1}][header]" value="{HEADER}" />
                        </div>

                        <div class="clear">
                        </div>

                        <div class="leftfloat textInputLabel topmargin">
                            <span>Bild-Beschreibung&nbsp;</span>
                        </div>

                        <div class="leftfloat">
                            <textarea name="Blogger[blogEntries][{INSTANCEKEY}][{KEY1}][text]" />{TEXT}</textarea>
                        </div>

                        <div class="clear">
                        </div>
                    </div>
                </div>
                {/PART BLOGENTRIES}
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
            instanceName: 'Blogger'
        });
    });
</script>