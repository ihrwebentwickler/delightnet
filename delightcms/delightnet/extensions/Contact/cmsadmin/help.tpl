<div>
    Plugin: Contact<br />
    Version: 2.1 (18.07.2012)<br />
    Autor: Gunnar von Spreckelsen, Ihr Webentwickler<br />
</div>
<div>
    Mit Contact ist ein Kontakt-Formular auf Ihrer Webseite integriert, es leistet folgende Dinge:<br />
    - vollständig individuell anpassbares Design der Kontakt-Seite<br />
    - Spam-Schutz mit echtem Captcha<br />
    - individuelle Einstellmöglichkeit, welche Daten Pflichtangaben sind<br />
    - Überprüfung auf vollständige Angaben (serverseitige Überprüfung)<br />
    - individuelle Konfiguration, z.B. beim Layout der Empfangs-Email<br />
    - echte Google-Map-Verlinkung zum Zeichnen einer Google-Map-Karte (ohne Iframe oder ähnliches)<br />
</div>
<hr />
<div>
    Sie können folgende Daten verwalten:<br />
    <br />
    <b>Empfangs-Email-Adresse</b><br />
    ist ihre Email-Empfangsadresse. An diese Adresse werden die
    Daten versendet, wenn Sie jemand über das Kontaktformular anschreibt.<br />
    <br />
    <b>Betreff</b> ist ihre eigene Email-Betreff-Angabe. Wenn Sie eine Email über das Kontakt-Formular erhalten,
    erkennen Sie den Eingang an Ihrer eigenen Betreffangabe.<br />
    <br />
    <b>Datei Fehler-Vorlage</b><br />
    ist der Dateiname des Seitenhelfers, der die
    Formatierungsangaben enth&auml;lt, wenn bei der &Uuml;bermittlung Fehler
    auftreten.<br />
    <br />
    <b>Datei Bestätigungs-Seite</b><br />
    wird automatisch als Best&auml;tigunsseite
    geladen, wenn das Kontaktformular erfolgreich versendet wurde, als Angabe
    dient der Dateiname.<br />
    <br />
    <b>individuelle Hilfs-Texte</b><br />
    dienen der Angabe von unterstützenden Hilfs-Texten für den Nutzer des Formulares:<br />
    <b>Willkommens-Nachricht</b> ist ein Willkommenstext zur Begrüßung des Formular-Benutzers,
    <b>Versende-Fehler</b> weist den Benutzer auf ein fehlerhaftes Ausfüllen hin, <b>Spam-Fehler</b>
    ist die speziellere Meldung, wenn die Eingabe des Spam-Codes falsch ist. Mit <b>Schrift-Farbe Fehler</b>
    geben Sie den Farbwert der Fehler-Schriftausgabe an (hexadezimal).<br />
    <br />
    Mit <b>Vorlage Email-Text</b> bearbeiten Sie den individuell anpassbaren
    Nachrichtentext, den Sie erhalten, wenn Sie jemand über das
    Kontakt-Formular anschreibt.<br />
    Dort geben Sie das Eingabefeld als Zahlenwert in Klammern an, z.B. als
    Marker {1}. In dem Marker-Beispiel ist {1} die Nummer 1 des verwendeten
    Eingabefeldes in der HTML-Vorlage. In dem HTML-Text wird diese Angabe
    mit name=&qout;emaildata[<b>1</b>]&qout; gesetzt.<br />
    <br />
    Es sind folgende Marker im Nachrichtentext nutzbar:<br />
    <b>{TIMESTAMP}</b> generiert Ihnen das Versand-Datum der Email.<br />
    <br />
    <b>{IP-ADRESS}</b> &uuml;bermittelt Ihnen die IP-Adresse des Absenders.<br />
    <br />
    <b>{SERVERADRESS}</b> enth&auml;lt Ihren Server-Namen, &uuml;ber den die
    Kontaktbereich-Angaben &uuml;bermittelt wurden.<br />
    <br />
    <b>{HOSTNAME}</b> beinhaltet den Host-Namen des Verwenders des Formulares.<br />
    <br />
    Unter <b>verwendete Formular-Elemente</b> geben Sie an, welche Formular-Eingaben
    vorhanden sind und welche davon Pflichtangaben sind, auch hier findet sich die
    gleiche Nummerierung vor wie in der HTML-Vorlage bzw. bei der Angabe zur Vorlage Email-Text.<br /> 
    <br />
    Im Bereich <b>Captcha</b> verwalten Sie die Captcha-Angaben:<br />
    <b>Dateilink Schrift</b> ist der Link auf die Schrift-Datei, aus der das Captcha-Bild
    generiert wird. <b>Schriftgröße und Schriftfarbe</b> formatieren dieses Captcha-Bild zusätzlich.
    <b>Hintergrund-Farbe</b> definiert die Hintergrund-Farbe des Captcha-Bildes. Unter
    <b>Anzahl der Literale</b> geben Sie die Anzahl der verwendeten einzelnen Literale an.<br />
    <br />
    Alle Farb-Angaben sind im Hexadezimal-Format zu tätigen.<br />
    <br />
    Im Bereich <b>Google-Map</b> verwalten Sie die Integration einer Google-Map-Karte.
    Unter Google-Link tragen Sie den Kartenauschnitt-Link ein, gehen Sie bei der Generierung
    folgendermaßen vor:<br />
    - Besuchen Sie maps.google.de<br />
    - Wählen Sie Ihren gewünschte Karte<br />
    - Klicken Sie links oben auf das Link-Symbol neben Orte und drucken<br />
    - Kopieren Sie den Link und speichern Sie Ihn hier unter Google-Link ab.<br />
    <br />
    Mit der Weiten- und Höhen-Angabe geben Sie die Dimensionen der Karte an.
</div>
<hr />
<div>
    <br />
    <b>Marker</b>:
    {MESSAGE} Ausgabe der Willkommens- oder Fehler-Meldungen<br />
    {CAPTCHAIMAGE} Ausspielen des Captcha-Bildes<br />
    {GOOGLEMAP_WIDTH} bzw. {GOOGLEMAP_HEIGHT} Ausspielen der Googlemap-Karte mit den Breiten- und Höhen-Dimensionen<br />
    {GOOGLEMAP_LINK} Ausspielen des Google-Map-Links zur Integration des gewünschten Karten-Auschnittes<br />
    {PART_KONTAKT} Integration spezifischer Daten<br />
    <br />
    <b>Seitenhelfer:</b><br />
    kontakt.tpl Integration der spezifischen Daten über {PART_KONTAKT}<br />
    emaildata.tpl Integration und Spichern von weiteren Umgebungs-Daten<br />
</div>