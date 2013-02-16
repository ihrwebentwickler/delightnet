<div id="gui">
    <form action="systemsettings.html" method="post" enctype="multipart/form-data">
        <input type="hidden" name="extname" value="{EXTNAME}" />
        <div class="headerText">
            <p>
                <span class="dynamicarrow">
                    &#9658;
                </span>
                <span>
                    <b>System-Info&nbsp;</b>
                </span>
            </p>
        </div>
        <div class="colorbox">
            <p>
                <span>System-Info Ausgabe aktiviert/ nicht aktiviert</span>
                <input type="checkbox" name="systemsettings['systeminfo']" /><br />
                Achtung: Bei Aktivierung werden bei jedem Seitenaufruf der Webseite
                Systemausgaben auf dem Bildschirm ausgegeben!
            </p>
        </div>
    </form>
</div>