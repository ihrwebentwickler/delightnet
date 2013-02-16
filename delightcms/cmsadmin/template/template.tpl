<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">

    <head>
        <meta name="language" content="german, de, deutsch" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

        <link rel="stylesheet" type="text/css" href="../cmsadmin/css/main.css" />
        <link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/ui-lightness/jquery-ui.css" />

        <script  type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script> 
        <script  type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js"></script>

        <meta http-equiv="CACHE-CONTROL" content="NO-CACHE"/>
        <script src="/cmsadmin/js/cmsadmin.js"></script>
        <script src="/cmsadmin/js/gui.js"></script>

        <script> 
            window.history.forward();
            function noBack() {
                window.history.forward();
            }
        </script>

        {DYNAMICFILEINTEGRATION}

        <link rel="shortcut icon" href="favicon.ico" />

        <meta name="author" content="Ihr Webentwickler,Gunnar von Spreckelsen" />
        <meta name="robots" content="noindex, nofollow" />

        <meta name="expires" content="never" />
        <meta name="description" content="Inhalts-Verwaltung DelightCms" />
    </head>
    <body onload="noBack();" onpageshow="if (event.persisted) noBack();" onunload="">

        <div id="sitecontent">
            <div id="head">
                <div id="headimage">
                    <div id="logoutcontainer">
                        <form action="logout.html" method="post" enctype="multipart/form-data">
                            <div>
                                <input class="button" type="submit" value="Ausloggen" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div id="navigationcontainer">    
                <div class="leftfloat">
                    <ul id="nav" class="leftfloat">
                        <li><a href="#">Inhalts-Pflege</a>
                            <ul>
                                <li><a href="content.html" title="Bearbeiten Sie einzelne Seiten der Webseite">Inhalts-Seiten</a></li>
                                <li><a href="css.html" title="CSS bearbeiten">CSS-Design</a></li>
                                <li><a href="index.php?cmd=iniedit&action=main" title="Bearbeiten Sie weitere Daten (Webseiten-Konfiguration)">Webseiten-Daten</a></li>
                                <li><a href="fmwebsite.html" title="Verwalten Sie Ihre Webseiten-Daten mit einem Dateimanager">DM Webinhalte</a></li>
                            </ul>
                        </li>
                        <li><a href="#">System-Verwaltung</a>
                            <ul>
                                <li><a href="systemsettings.html" title="Einstellungen dieses Pflegeprogramm">System-Einstellungen</a></li>
                                <li><a href="password.html" title="Ändern Sie Ihren Benutzernamen und Passwort">BN/ Passwort ändern</a></li>
                                <li><a href="webexport.html" title="Exportieren und Sichern Sie Ihre Webseite lokal auf dem eigenen Pc">Daten-Sicherung</a></li>
                            </ul>
                        </li>
                        <li><a href="#">Erweiterungen</a>
                            <ul>
                                <li><a href="index.php?cmd=iniedit&action=extensions" title="Verwaltung der Erweiterungen">Erw-Verwaltung</a></li>
                                <li><a href="#">aktuell installiert &gt;</a>
                                    <ul>
                                        {MENU_EXTCONF}
                                        <li><a href="index.php?cmd=iniedit&action=Contact" title="Konfiguration der Erweiterung Contact">Kontakt-Form</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li><a href="#">Hilfe</a>
                            <ul>
                                <li><a href="introduction.html" title="Herzlich Willkommen, lesen Sie die Einführung in das System">Einführung</a></li>
                                <li><a href="book.html" title="allgemeines Benutzerhandbuch">Benutzer-Handbuch</a></li>
                                <li><a href="webblockbook.html" title="Erläuterung der Marker">Handbuch Marker</a></li>
                                <li><a href="statistics.html" title="aktuelle Statistik-Daten">Statistik-Daten</a></li>
                                <li><a href="#">Erweiterungen &gt;</a>
                                    <ul>
                                        {MENU_EXTHELP}
                                    </ul>
                                </li>
                                <li><a href="systeminfo.html" title="System-Info/ Lizenz">System-Info/ Lizenz</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="rightfloat" id="langmenu">
                    <div class="rightfloat" id="flaglinks">
                        <a href="index.php?action=1&cmd=language"><img class="{CHOISED_LANG_D}" src="/cmsadmin/images/lang/flags/d.png" alt="" width="42" height="28" /></a>
                        <a href="index.php?action=2&cmd=language"><img class="{CHOISED_LANG_GB}" src="/cmsadmin/images/lang/flags/gb.png" alt="" width="42" height="28" /></a>
                    </div>
                    <div class="clear">
                    </div>
                </div>
                <div class="clear">
                </div>
            </div>

            <div class="clear">
            </div>

            <div id="content">
                <div id="breadcrumb">
                    <p>
                        {BREADCRUMB}
                    </p>
                </div>
                {CONTENT}
            </div>
        </div>
    </body>
</html>