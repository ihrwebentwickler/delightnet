<div id="gui">
<div id="messages">
    <p id="newInstanceMessage">
        Ein neues Kontakt-Formular wurde angelegt.
    </p>

    <p id="deleteInstanceMessage">
        Das Kontakt-Formular Nr. {MESSAGE_NB_INSTANCE} wurde entfernt.
    </p>

    <p id="newEntryMessage">
        Ein neues Kontakt-Formular wurde erstellt. Bitte bearbeiten Sie jetzt die Daten der neuen Instance.
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
                        <img src="/cmsadmin/images/listchoise_new.png" alt=""/>
                    </span>
                    <span id="deleteInstance">
                        <span>&nbsp;&nbsp;&nbsp;Player löschen</span>
                        <img src="/cmsadmin/images/listchoise_delete.png" alt=""/>
                    </span>
                </span>

                <span id="selectEntry">
                    <span id="newEntry" class="leftmarginBig">
                        <span>Media neu</span>
                        <img src="/cmsadmin/images/listchoise_new.png" alt=""/>
                    </span>

                    <span id="deleteEntry">
                        <span>&nbsp;&nbsp;&nbsp;Media löschen</span>
                        <img src="/cmsadmin/images/listchoise_delete.png" alt=""/>
                    </span>
                </span>
    </p>
</div>
<form action="contactform.html" method="post" enctype="multipart/form-data">
<div class="unitbox">
    <p>
        <span class="dynamicarrow_box">&#9658;</span>
        <span>&nbsp;<b>Empfangs-Email-Adresse</b> - Die Emailadresse, an der die Kontaktformular-Daten gesendet werden</b></span>
        http://www.tipps-archiv.de/google-maps-laengengrad-breitengra.html

        In der normalen Ansicht sind Zoomstufen
        zwischen 0 (niedrigste Zoomstufe, bei der der Globus vollständig
        angezeigt wird) und 21+ (höchste Zoomstufe, bei der einzelne Gebäude
        zu sehen sind) möglich.
    </p>
</div>
<div class="lineboxGui off">
    <div>
        <div class="leftfloat textInputLabel">
            <ul>
                <li><span>Email-Adresse</span></li>
            </ul>
        </div>

        <div class="leftfloat">
            <ul>
                <li><input type="text" name="contactform[emaildata][emailTo]" value="{CONTACTFORM_EMAIL_EMAILTO}"/></li>
            </ul>
        </div>
    </div>
    <div class="clear">
    </div>
</div>
<div class="topmargin">
</div>
<div class="unitbox">
    <p>
        <span class="dynamicarrow_box">&#9658;</span>
        <span>&nbsp;<b>Betreff</b> - Der Betreff (Subject) der Empfangs-Email</span>
    </p>
</div>
<div class="lineboxGui off">
    <div>
        <div class="leftfloat textInputLabel">
            <ul>
                <li><span>Betreff</span></li>
            </ul>
        </div>
        <div class="leftfloat">
            <ul>
                <li><input type="text" name="contactform[emaildata][subject]" value="{CONTACTFORM_EMAIL_SUBJECT}"/></li>
            </ul>
        </div>
    </div>

    <div class="clear">
    </div>
</div>
<div class="topmargin">
</div>
<div class="unitbox">
    <p>
        <span class="dynamicarrow_box">&#9658;</span>
        <span>&nbsp;<b>Datei Fehler-Vorlage</b> - Datei-Angabe mit den Formatierungs-Anweisungen für fehlerhaftes Kontakt-Formular-Versenden</span>
    </p>
</div>
<div class="lineboxGui off">
    <div>
        <div class="leftfloat textInputLabel">
            <ul>
                <li><span>Datei Fehler-Seite</span></li>
            </ul>
        </div>
        <div class="leftfloat">
            <ul>
                <li><input type="text" name="contactform[emaildata][ErrorSenderPart]"
                           value="{CONTACTFORM_EMAIL_ERRORSENDERPART}"/></li>
            </ul>
        </div>
    </div>
    <div class="clear">
    </div>
</div>
<div class="topmargin">
</div>
<div class="unitbox">
    <p>
        <span class="dynamicarrow_box">&#9658;</span>
        <span>&nbsp;<b>Datei Bestätigungs-Seite</b> - Datei-Angabe zum Laden der Bestätigungs-Seite bei erfolgreichem Kontakt-Formular-Versand</span>
    </p>
</div>
<div class="lineboxGui off">
    <div>
        <div class="leftfloat textInputLabel">
            <ul>
                <li><span>Datei Bestätigungsseite</span></li>
            </ul>
        </div>
        <div class="leftfloat">
            <ul>
                <li><input type="text" name="contactform[emaildata][ConfirmationSiteFileName]"
                           value="{CONTACTFORM_EMAIL_CONFIRMATIONSITEFILENAME}"/></li>
            </ul>
        </div>
    </div>
    <div class="clear">
    </div>
</div>
<div class="topmargin">
</div>
<div class="unitbox">
    <p>
        <span class="dynamicarrow_box">&#9658;</span>
        <span>&nbsp;<b>invididuelle Hilfs-Texte</b> - Meldungen und Fehler-Farbe definieren</span>
    </p>
</div>
<div class="lineboxGui off">
    <div>
        <div class="leftfloat textInputLabel">
            <ul>
                <li><span>Willkommens-Nachricht</span></li>
                <li><span>Versende-Fehler</span></li>
                <li><span>Spam-Fehler</span></li>
                <li><span>Schrift-Farbe Fehler</span></li>
                <li><span>Formatierung Fehler</span></li>
            </ul>
        </div>
        <div class="leftfloat">
            <ul>
                <li><input type="text" name="contactform[message][messageStart]"
                           value="{CONTACTFORM_MESSAGE_MESSAGESTART}"/></li>
                <li><input type="text" name="contactform[message][standardError]"
                           value="{CONTACTFORM_MESSAGE_STANDARDERROR}"/></li>
                <li><input type="text" name="contactform[message][spamKeyError]"
                           value="{CONTACTFORM_MESSAGE_SPAMKEYERROR}"/></li>
                <li><input type="text" name="contactform[message][fontColorbyError]"
                           value="{CONTACTFORM_MESSAGE_FONTCOLORBYERROR}"/></li>
                <li><input type="text" name="contactform[message][styleInputFieldError]"
                           value="{CONTACTFORM_MESSAGE_STYLEINPUFIELDERROR}"/></li>
            </ul>
        </div>
    </div>
    <div class="clear">
    </div>
</div>
<div class="topmargin">
</div>
<div class="unitbox">
    <p>
        <span class="dynamicarrow_box">&#9658;</span>
        <span>&nbsp;<b>Vorlage Email-Text</b> - Text-Vorlage des Inhaltes der Empfangs-Email </span>
    </p>
</div>
<div class="lineboxGui off">
    <div>
        <div class="leftfloat textInputLabel middleareaContent">
            <ul>
                <li><span>Email-Empfangstext</span></li>
            </ul>
        </div>
        <div class="leftfloat">
            <textarea class="middlearea"
                      name="contactform[email][messagetext]">{CONTACTFORM_EMAIL_MESSAGETEXT}</textarea>
        </div>
        <div class="clear">
        </div>
        <div class="leftfloat textInputLabel">
            &nbsp;
        </div>
        <div class="leftfloat borderbox_dynamicwidth topmargin">
            <p>
                <b>Kurz-Erläuterung der Marker</b><br>
                <b>{TIMESTAMP}</b> - Versand-Datum der Email.<br>
                <b>{IP-ADRESS}</b> - IP-Adresse des Absenders.<br>
                <b>{SERVERADRESS}</b> - Server-Namen vom Absender des Kontakt-Formulares.<br>
                <b>{HOSTNAME}</b> - Host-Namen des Verwenders des Formulares.
            </p>
        </div>
        <div class="clear">
        </div>
        <div class="topmarginSmall">
        </div>
    </div>
</div>
<div class="topmargin">
</div>
<div class="unitbox">
    <p>
        <span class="dynamicarrow_box">&#9658;</span>
        <span>&nbsp;<b>verwendete Formular-Elemente</b> - die Bezeichnung bzw. Definition der Formular-Elemente</span>
    </p>
</div>
<div class="lineboxGui off">
    <div>
        <div class="leftfloat textInputLabel">
            <ul>
                <li><span>Form Nr.</span></li>
            </ul>
        </div>
        <div class="leftfloat">
            <ul>
                {CONTACTFORM_FORM_OBLIGATION&MARKER}
            </ul>
        </div>
    </div>
    <div class="clear">
    </div>
</div>
<div class="topmargin">
</div>
<div class="unitbox">
    <p>
        <span class="dynamicarrow_box">&#9658;</span>
        <span>&nbsp;<b>Captcha</b> - Formatierung und Definition</span>
    </p>
</div>
<div class="lineboxGui off">
    <div>
        <div class="leftfloat textInputLabel">
            <ul>
                <li><span>Dateilink Schrift</span></li>
                <li><span>Schriftgröße</span></li>
                <li><span>Schriftfarbe</span></li>
                <li><span>Hintergrundfarbe</span></li>
                <li><span>Anzahl der Literale</span></li>
            </ul>
        </div>
        <div class="leftfloat">
            <ul>
                <li><input type="text" name="contactform[captcha][font]" value="{CONTACTFORM_CAPTCHA_FONT}"/></li>
                <li><input type="text" name="contactform[captcha][fontSize]" value="{CONTACTFORM_CAPTCHA_FONTSIZE}"/>
                </li>
                <li><input type="text" name="contactform[captcha][fontColor]" value="{CONTACTFORM_CAPTCHA_FONTCOLOR}"/>
                </li>
                <li><input type="text" name="contactform[captcha][backgroundColor]"
                           value="{CONTACTFORM_CAPTCHA_BACKGROUNDCOLOR}"/></li>
                <li><input type="text" name="contactform[captcha][number]" value="{CONTACTFORM_CAPTCHA_NUMBER}"/></li>
            </ul>
        </div>
    </div>
    <div class="clear">
    </div>
</div>
<div class="topmargin leftfloat">
    <input type="submit" class="button1" value="Speichern" name="contactformdata">
</div>

{SAVEBUTTON_PAGEHELPER}

<div class="clear">
</div>
</form>
</div>
</div>