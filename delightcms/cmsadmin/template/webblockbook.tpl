<div>
    <img src="/cmsadmin/images/help/help4.jpg" alt="" width="360" height="100" />
</div>

<div>
    <p class="big">
        Handbuch zur Erläuterung der Standard-Marker
    </p>
</div>

<div class="topmarginSmall">
    <p>
        Unter einem Marker versteht man eine Markierung im Quelltext von Seiten.
        Diese Makierung dient dazu, dort dynamiche Inhalte einzuf&uuml;gen,
        die vom System generiert werden.<br />
        <br />
        Bitte lassen Sie diese Marker immer im Quellcode stehen, auch wenn sie den
        einen oder anderen Maker nicht ben&ouml;tigen. Ich kann Sie auch
        beruhigen, denn es gibt nur eine normale Datei, die Marker enthält,
        so dass Sie kaum mit dieser Technologie in Ber&uuml;hrung kommen
        werden, n&auml;mlich die Vorlage.<br />
        <br />
        Merken Sie sich als Ged&auml;chnist&uuml;tze bitte nur folgendes: Seien Sie
        bei der Bearbeitung der Vorlagedatei (i.d.R. template.tpl) vorsichtig und
        nehmen Sie dort keine Marker heraus.<br />
        <br />
        Denn fast alle Marker sind logischweise Bestandteil der
        Schablonen-Vorlagen-Datei und in
        Seitenhelfern. Da die Hauptvorlage der Webseite mit template.tpl als
        Schablone f&uuml;r alle weiteren dynamischen Inhalte dient, ist es
        nachzuvollziehen, das nur diese Datei die meisten Marker enth&auml;lt.<br />
        <br />
        Seitenhelfer, die
        zus&auml;tzlich noch in normale einzelne Seiten eingebunden werden, enthalten
        manchmal Marker, weil sie im Gegnensatz zu den normalen Seiten noch
        etwas technischer zu betrachten sind. Ansonsten enthalten die
        Inhalts-Seiten der Erweiterungen weitere individuelle Marker.<br />
        <br />
        Also zusammengefasst:<br />
        - Die Datei template.tpl enth&auml;lt als Hauptschablone Marker, seien Sie dort
        bitte aufmerksam, da Sie direkt editierbar ist.<br />
        <br />
        - Keine normale Inhaltsseite enth&auml;lt Marker.<br />
        <br />
        - Seitenhelfer enthalten manchmal Marker.<br />
        <br />
        - es sind manchmal weitere komplexere -Marker- enthalten, z.B. für Schleifen.<br />
        <br />
        Erl&auml;uterungen zu den hinterlegten Inhalten der Marker entnehmen Sie dem
        Handbuch Webdaten, es sind letzendlich im allgemeinen die Webdaten, die an
        diese Stellen integriert werden.<br />
        <br />
        <b>Marker der
            Vorlagen-Schablonen-Datei mit dem Namen template.tpl:</b><br />
        <br />
        <b>&#123;MULTIDESIGN}</b><br />
        Einblendung der Multidesign-Funktionalit&auml;t.<br />
        <br />
        <b>&#123;DYNAMICFILEINTEGRATION}</b><br />
        Einblendung von dynamisch geladenen zus&auml;tzlichen Dateien wie z.B.
        Javascript-Dateien.<br />
        <br />
        <b>&#123;SITETITLE}</b><br />
        Ausspielen des Seitentitels der aktuellen Seite.<br />
        <br />
        <b>&#123;CONTENT}</b><br />
        Integation der dynamischen Inhalts-Seiten an dieser Stelle wie z.B.
        das Impressum.<br />
        <br />
        <b>&#123;LOOP name=&quot;menu1&quot;}
            &lt;li&gt;
            &lt;a href=&quot;/{FILENAME}.html&quot;
            title=&quot;{SITE}&quot;&gt;{SITE}&lt;/a&gt;
            &lt;/li&gt;
            &#123;/LOOP}</b><br />
        Es handelt sich hier um eine Schleife, der
        Bereich zwischen {LOOP} und {/LOOP} wird immer wiederholt, so wird
        das gesamte Menu ausgespielt. Das Menu hat hier den Namen menu1, im
        Pflegebereich entspricht das der Eintragung vom ersten Menü.<br />
        <br />
        <b>&#123;OUTPUT_SYSTEMINFO}</b><br />
        Der Marker dient als technische Hilfe, wenn Sie über das Menü
        System-Einstellungen System-Infos ausgeben lassen.<br />
        <br />
        <b>&#123;GOOGLESITEVERIFICATION}</b><br />
        Wenn Sie bei Google für die Google-Site-Verification angemeldet sind
        und die Funktion aktivert haben, werden Ihre Google-ID-Daten an dieser
        Stelle ausgespielt.<br />
        <br />
        <b>&#123;LANGNAVIGATION}</b><br />
        Ausspielung des Multisprachen-Menüs mit Landesfahnen.<br />
        <br />
        <b>&#123;GOOGLEANALYTICS}</b><br />
        Bei aktiviertem Google-Analytics wird Ihnen hier die entsprechende
        Google-Code-Umgebung zur Verfügung gestellt.<br />
        <br />
        <b>Marker der Contact-Datei /public/extensions/Contact/template/Contact.tpl:</b><br />
        <br />
        <b>&#123;MESSAGE}</b><br />
        Hier werden die Willkommens-Nachricht oder Warnhinweise ausgegeben.<br />
        <br />
        <b>&#123;GOOGLEMAP_WIDTH} und &#123;GOOGLEMAP_HEIGHT}</b><br />
        Die Ausgabe Ihrer gewünschten Breite und Höhe für das Generieren
        der Googlemaps-Karte.<br />
        <br />
        <b>&#123;PART_KONTAKT}</b><br />
        Hier werden einige technische Hilfsdaten zum Funktionsumfang der Kontakt-
        Erweiterung automatisiert ausgegeben.<br />
        <br />
        <b>&#123;GOOGLEMAP_ZOOM}</b><br />
        Ausspielen Ihres gewünschten Zoom-Faktors beim ersten Rendern der Google-
        Maps-Karte. In der normalen Ansicht sind Zoomstufen
        zwischen 0 (niedrigste Zoomstufe, bei der der Globus vollständig
        angezeigt wird) und 21+ (höchste Zoomstufe, bei der einzelne Gebäude
        zu sehen sind) möglich.<br />
        <br />
        <b>&#123;GOOGLEMAP_LATLNG}</b><br />
        Die Koordinaten-Ausgabe, welcher Kartenausschnitt ausgespielt
        werden soll.
    </p>
</div>