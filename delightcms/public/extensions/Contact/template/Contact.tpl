<div class="leftfloat">
    <div id="contact1" class="contactform">
        <p class="message" style="{CONTACT_STYLE_ERROR}">
            {CONTACT_MESSAGE}
        </p>

        <div class="clear">
        </div>

        <form action="kontakt.html" method="post" name="mainform">
            <label>Vorname:</label>
            <input type="text" name="emaildata[1]" />

            <label>Nachname:</label>
            <input type="text" name="emaildata[2]" />

            <label>Email:</label>
            <input type="text" name="emaildata[3]" />

            <label>Telefon:</label>
            <input type="text" name="emaildata[4]" />

            <label>Message:</label><br />
            <textarea name="emaildata[5]" cols="40" rows="8"></textarea>

            <label><span>Captcha: </span><span>{CAPTCHAIMAGE}</span></label>
            <input type="text" name="captcha" />
            <input type="hidden" name="captchaKey" value="{CAPTCHAKEY}" />

            <div>
                <button value="submit-value" name="submit" type="submit" aria-disabled="false">Absenden</button>
            </div>
        </form>

        <div class="wait">
            <img src="/public/extensions/Contact/public/images/wait.gif">
        </div>
    </div>

    <div class="clear">
    </div>
</div>

<div class="contactdata leftfloat">
    <div class="googlemap">
        <div id="map_canvas1" style="width: {GOOGLEMAP_WIDTH}px;height: {GOOGLEMAP_HEIGHT}px;">
        </div>
    </div>
    <div>
        <p>
            Musterfirma Name<br />
            Inh.: Name Mustermann<br />
            D - 123456 Musterstadt<br />
            Musterstrasse 23<br />
            <br />
            eMail: info[ at ]musterfirma.de<br />
            Telefon: 01234 56789010
        </p>
    </div>
</div>

<div class="clear">
</div>

{PART_KONTAKT}