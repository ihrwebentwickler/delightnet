<div id="gui">
    <form action="extconf.html" method="post" enctype="multipart/form-data">
        <div class="headerText">
            <p>
                <span class="dynamicarrow">
                    &#9658;
                </span>
                <span>
                    <b>Namen der wichtigsten Dateien&nbsp;</b>
                </span>
            </p>
        </div>
        <div class="colorbox">    
            <div>
                <div class="leftfloat textInputLabel">
                    <span>Datei Home-Seite&nbsp;</span>
                </div>

                <div class="leftfloat">
                    <input type="text" name="homesite['file']" value="{HOMESITE_FILE}" />
                </div>
            </div>

            <div class="clear">
            </div>


            <div>
                <div class="leftfloat textInputLabel">
                    <span>Datei Menü-Style&nbsp;</span>
                </div>

                <div class="leftfloat">
                    <input type="text" name="menustyle['file']" value="{MENUSTYLE_FILE}" />
                </div>
            </div>

            <div class="clear">
            </div>
        </div>
        <div class="topmarginBig headerText">
            <p>
                <span class="dynamicarrow">
                    &#9658;
                </span>
                <span>
                    <b>Menu</b> - Verwaltung der Menus mit Einträgen der jeweiligen Menü-Punkte und Dateinamen
                </span>
            </p>
        </div>
        <div class="colorbox">      
            <div class="topmarginSmall leftfloat">
                <p>
                    <span>&nbsp;Auswahl Menü:</br></span>
                </p>
                <div class="menuChoiceList">
                    <span id="m1"><b>menu1</b></br></span>
                    <span id="m2">menu2</br></span>
                    <span id="m3">menu3</br></span>
                </div>
            </div>
            <div class="topmarginSmall leftfloat leftmargin">
                <p>
                    <br />
                    <span>aktuell gewählt: </span><span id="currentMenu"><b>Menu1</b></span>
                    <span class="leftmargin">neues Menu hinzufügen:</span>
                    <img id="newMenu" src="/cmsadmin/images/listchoise_new.png" alt="" />
                    <span class="leftmargin">dieses Menu löschen:</span>
                    <img id="newMenu" src="/cmsadmin/images/listchoise_delete.png" alt="" />
                </p>
            </div>

            <div class="clear">
            </div>

            <p>
                <br />
            </p>

            <div class="inputTextHalfSize">
                <ul>
                    <li>
                        <img src="/cmsadmin/images/listchoise_hand.png" alt="" /><span>&nbsp;&nbsp;Menü-Link auf Datei&nbsp;</span>
                        <select name="menu.filename[1]" size="1">
                            <option>Auswahl:</option>
                            <option>Heino</option>
                            <option>Michael Jackson</option>
                            <option>Tom Waits</option>
                        </select>
                        <span class="leftmargin">Menu-Bezeichnung&nbsp;</span><input type="text" name="menu.site[1][1]" /><span>&nbsp;&nbsp;</span><img src="/cmsadmin/images/listchoise_delete.png" alt="" />
                    </li>
                    <li>
                        <img src="/cmsadmin/images/listchoise_hand.png" alt="" /><span>&nbsp;&nbsp;Menü-Link auf Datei&nbsp;</span>
                        <select name="menu.filename[2]" size="1">
                            <option>Auswahl:</option>
                            <option>Heino</option>
                            <option>Michael Jackson</option>
                            <option>Tom Waits</option>
                        </select>
                        <span class="leftmargin">Menu-Bezeichnung&nbsp;</span><input type="text" name="menu.site[1][1]" /><span>&nbsp;&nbsp;</span><img src="/cmsadmin/images/listchoise_delete.png" alt="" />
                    </li>
                    <li>
                        <img src="/cmsadmin/images/listchoise_hand.png" alt="" /><span>&nbsp;&nbsp;Menü-Link auf Datei&nbsp;</span>
                        <select name="menu.filename[3]" size="1">
                            <option>Auswahl:</option>
                            <option>Heino</option>
                            <option>Michael Jackson</option>
                            <option>Tom Waits</option>
                        </select>
                        <span class="leftmargin">Menu-Bezeichnung&nbsp;</span><input type="text" name="menu.site[1][1]" /><span>&nbsp;&nbsp;</span><img src="/cmsadmin/images/listchoise_delete.png" alt="" />
                    </li>
                </ul>
                <ul>
                    <li>
                        <span>neuen Menu-Eintrag hinzufügen:&nbsp;</span><img id="newMenuEntry" src="/cmsadmin/images/listchoise_new.png" alt="" />
                    </li>
                </ul>
            </div>
        </div>
        <div class="headerText topmarginBig">
                <p>
                    <span class="dynamicarrow">
                        &#9658;
                    </span>
                    <span>
                        <span><b>Multi-Design</b> - dynamische Bildschirm-Auflösung aktiv/ nicht aktiv</span>
                        <input type="checkbox" name="multidesign['activ']" />
                    </span>
                </p>
        </div>
        <div class="colorbox">
            <div class="leftfloat textInputLabel">
                <span>1024x768&nbsp;</span>
            </div>

            <div class="leftfloat">
                <input type="checkbox" name="multidesign['modus']['1024x768']" />
            </div>

            <div class="clear">
            </div>

            <div class="leftfloat textInputLabel">
                <span>555x666&nbsp;</span>
            </div>

            <div class="leftfloat">
                <input type="checkbox" name="multidesign['modus']['555x666']" />
            </div>

            <div class="clear">
            </div>

            <div class="leftfloat textInputLabel">
                <span>777x888&nbsp;</span>
            </div>

            <div class="leftfloat">
                <input type="checkbox" name="multidesign['modus']['777x888']" />
            </div>

            <div class="clear">
            </div>
        </div>
        <div class="headerText topmarginBig">
                <p>
                    <span class="dynamicarrow">
                        &#9658;
                    </span>
                    <span>
                        <b>Seiten-Titel</b> - SEO-Seiten-Titel-Angabe
                    </span>
                </p>
        </div>
        <div class="colorbox">      
            <div class="inputTextHalfSize">
                <ul>
                    <li>
                        <span>Datei-Name&nbsp;</span>
                        <select name="menu.sitetitle[1]" size="1">
                            <option>Auswahl:</option>
                            <option>Heino</option>
                            <option>Michael Jackson</option>
                            <option>Tom Waits</option>
                        </select>        
                        <span class="leftmargin">Seiten-Titel&nbsp;</span><input type="text" name="sitetitle[1]['value']" /><span>&nbsp;&nbsp;</span><img src="/cmsadmin/images/listchoise_delete.png" alt="" />
                    </li>
                    <li>
                        <span>Datei-Name&nbsp;</span>
                        <select name="menu.sitetitle[2]" size="1">
                            <option>Auswahl:</option>
                            <option>Heino</option>
                            <option>Michael Jackson</option>
                            <option>Tom Waits</option>
                        </select>        
                        <span class="leftmargin">Seiten-Titel&nbsp;</span><input type="text" name="sitetitle[2]['value']" /><span>&nbsp;&nbsp;</span><img src="/cmsadmin/images/listchoise_delete.png" alt="" />
                    </li>
                    <li>
                        <span>Datei-Name&nbsp;</span>
                        <select name="menu.sitetitle[3]" size="1">
                            <option>Auswahl:</option>
                            <option>Heino</option>
                            <option>Michael Jackson</option>
                            <option>Tom Waits</option>
                        </select>        
                        <span class="leftmargin">Seiten-Titel&nbsp;</span><input type="text" name="sitetitle[3]['value']" /><span>&nbsp;&nbsp;</span><img src="/cmsadmin/images/listchoise_delete.png" alt="" />
                    </li>
                </ul>
                <ul>
                    <li>
                        <span>neuen Titel hinzufügen:&nbsp;</span><img id="newMenuEntry" src="/cmsadmin/images/listchoise_new.png" alt="" />
                    </li>
                </ul>
            </div>
        </div>
        <div class="headerText topmarginBig">
                <p>
                    <span class="dynamicarrow">
                        &#9658;
                    </span>
                    <span>
                        <b>Dynamisches Datei-Einladen in normale Webumgebung</b> - JS- und/oder CSS-Dateien in Einzelseiten
                    </span>
                </p>
        </div>
        <div class="colorbox">      
            <div class="inputTextHalfSize">
                <ul>
                    <li>
                        <span>Datei-Name&nbsp;</span>
                        <select name=dynamicFiles['key']['value'][1]" size="1">                      
                            <option>Auswahl:</option>
                            <option>Heino</option>
                            <option>Michael Jackson</option>
                            <option>Tom Waits</option>
                        </select>        
                        <span class="leftmargin">CSS- oder JS-Link&nbsp;</span><input type="text" name="dynamicFiles['value'][1]" /><span>&nbsp;&nbsp;</span><img src="/cmsadmin/images/listchoise_delete.png" alt="" />
                    </li>
                    <li>
                        <span>Datei-Name&nbsp;</span>
                        <select name=dynamicFiles['key']['value'][2]" size="1">
                            <option>Auswahl:</option>
                            <option>Heino</option>
                            <option>Michael Jackson</option>
                            <option>Tom Waits</option>
                        </select>        
                        <span class="leftmargin">CSS- oder JS-Link&nbsp;</span><input type="text" name=dynamicFiles['value'][2]" /><span>&nbsp;&nbsp;</span><img src="/cmsadmin/images/listchoise_delete.png" alt="" />
                    </li>
                    <li>
                        <span>Datei-Name&nbsp;</span>
                        <select name=dynamicFiles['key']['value'][3]" size="1">
                            <option>Auswahl:</option>>
                            <option>Heino</option>
                            <option>Michael Jackson</option>
                            <option>Tom Waits</option>
                        </select>        
                        <span class="leftmargin">CSS- oder JS-Link&nbsp;</span><input type="text" name="dynamicFiles['value'][3]" /><span>&nbsp;&nbsp;</span><img src="/cmsadmin/images/listchoise_delete.png" alt="" />
                    </li>
                </ul>
                <ul>
                    <li>
                        <span>neuen Titel hinzufügen:&nbsp;</span><img id="newMenuEntry" src="/cmsadmin/images/listchoise_new.png" alt="" />
                    </li>
                </ul>
            </div>
        </div>
        <div class="headerText topmarginBig">
            <p>
                <span class="dynamicarrow">
                    &#9658;
                </span>
                <span>
                    <b>Dynamisches Datei-Einladen in Devices-Env</b> - JS- und/oder CSS-Dateien in Devices
                </span>
            </p>
        </div>
        <div class="colorbox">      
            <div class="inputTextHalfSize">
                <ul>
                    <li>
                        <span>Datei-Name&nbsp;</span>
                        <select name=dynamicDeviceFiles['key']['value'][1]" size="1">
                            <option>Auswahl:</option>
                            <option>Heino</option>
                            <option>Michael Jackson</option>
                            <option>Tom Waits</option>
                        </select>        
                        <span class="leftmargin">CSS- oder JS-Link&nbsp;</span><input type="text" name="dynamicDeviceFiles['value'][1]" /><span>&nbsp;&nbsp;</span><img src="/cmsadmin/images/listchoise_delete.png" alt="" />
                    </li>
                    <li>
                        <span>Datei-Name&nbsp;</span>
                        <select name=dynamicDeviceFiles['key']['value'][2]" size="1">
                            <option>Auswahl:</option>
                            <option>Heino</option>
                            <option>Michael Jackson</option>
                            <option>Tom Waits</option>
                        </select>        
                        <span class="leftmargin">CSS- oder JS-Link&nbsp;</span><input type="text" name=dynamicDeviceFiles['value'][2]" /><span>&nbsp;&nbsp;</span><img src="/cmsadmin/images/listchoise_delete.png" alt="" />
                    </li>
                    <li>
                        <span>Datei-Name&nbsp;</span>
                        <select name=dynamicDeviceFiles['key']['value'][3]" size="1">
                            <option>Auswahl:</option>
                            <option>Heino</option>
                            <option>Michael Jackson</option>
                            <option>Tom Waits</option>
                        </select>        
                        <span class="leftmargin">CSS- oder JS-Link&nbsp;</span><input type="text" name="dynamicDeviceFiles['value'][3]" /><span>&nbsp;&nbsp;</span><img src="/cmsadmin/images/listchoise_delete.png" alt="" />
                    </li>
                </ul>
                <ul>
                    <li>
                        <span>neuen Titel hinzufügen:&nbsp;</span><img id="newMenuEntry" src="/cmsadmin/images/listchoise_new.png" alt="" />
                    </li>
                </ul>
            </div>
        </div>
        <div class="headerText topmarginBig">
                <p>
                    <span class="dynamicarrow">
                        &#9658;
                    </span>
                    <span><b>Google - Site-Verfication und Analytics</b></span>
                    <span class="leftmargin">Site-Verfication aktiv/ nicht aktiv</span><input type="checkbox" name="siteverfication" />
                    <span class="leftmargin">Analytics aktiv/ nicht aktiv</span><input type="checkbox" name="googleanalytics" />
                </p>
        </div>
        <div class="colorbox">
            <div>
                <div class="leftfloat textInputLabel">
                    <span>Verification-ID&nbsp;</span>
                </div>

                <div class="leftfloat">
                    <input type="text" name="siteverficationId" value="{HOMESITE_FILE}" />
                </div>
            </div>

            <div class="clear">
            </div>

            <div>
                <div class="leftfloat textInputLabel">
                    <span>Analytics&nbsp;</span>
                </div>

                <div class="leftfloat">
                    <input type="text" name="googleanalyticsId" value="{TEMPLATE_FILE}" />
                </div>
            </div>

            <div class="clear">
            </div>
        </div>

        <div class="topmargin leftfloat">
            <input class="button" type="submit" value="Speichern" />
        </div>

        {SAVEBUTTON_PAGEHELPER}

        <div class="clear">
        </div>
    </form>
</div>