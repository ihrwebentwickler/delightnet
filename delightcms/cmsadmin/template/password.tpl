{PASSWORDERROR}

<div class="colorbox">
    <form action="password.html" method="post" enctype="multipart/form-data">
        <div>
            <p class="big">
                Geben Sie Ihr aktuelles Passwort, einen neuen gewünschten
                Benutzernamen und ein neues gewünschtes Passwort an. Wenn Sie das
                Feld -neuen Benutzer- leer lassen, bleibt Ihr ursprünglicher
                Benutzernamen erhalten. Nach dem erfolgreichen Ändern der Daten
                werden Sie automatisch ausgeloggt, Sie können sich dann direkt
                mit den neuen Anmelde-Daten am System anmelden.<br />
                <br />
                Folgende Literale für Ihren Benutzernamen und Ihr Passwort sind
                gültig:<br />
                a-z, A-Z, 0-9, äöüÄÖÜ§+-!*#@ß
            </p>
            <p class="topmargin">
                &nbsp;aktuelles Passwort:</br>
                <input type="text" name="pw_old" />
            </p>
            <p class="topmargin">
                &nbsp;neuen Benutzer (falls gewünscht):</br>
                <input type="text" name="user_new" />
            </p>
            <p class="topmarginSmall">
                &nbsp;neues Passwort:</br>
                <input type="text" name="pw_new" />
            </p>
            <p class="topmargin">
                <input class="button" name="action_pw" type="submit" value="Speichern" />
            </p>
        </div>
    </form>
</div>