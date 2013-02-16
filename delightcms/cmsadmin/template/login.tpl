<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">

    <head>
        <meta name="language" content="german, de, deutsch" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

        <link rel="shortcut icon" href="favicon.ico" />
        <link rel="stylesheet" type="text/css" href="/cmsadmin/css/main.css" />
        <title>Startseite CMS</title>

        <meta name="author" content="Ihr Webentwickler,Gunnar von Spreckelsen" />
        <meta name="robots" content="noindex, nofollow" />

        <meta name="description" content="Inhalts-Verwaltung" />
        <meta http-equiv="CACHE-CONTROL" content="NO-CACHE"/>

        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script> 
        <script type="text/javascript" src="/cmsadmin/js/login.js"></script>
    </head>
    <body>
        <div id="login">
            <form action="welcome.html" method="post" enctype="multipart/form-data">
                <div class="loginfield">
                    <p class="big">
                        {LOGMESSAGE}
                    </p>

                    <p class="topmargin">
                        Benutzer:</br>
                        <input type="text" name="user" id="user" />
                    </p>

                    <p class="topmarginSmall">
                        Passwort:</br>
                        <input type="password" name="password" id="passwort" />
                    </p>

                    <p class="topmargin">
                        <input class="button" name="loginbtn" type="submit" value="Einloggen" />
                        <span></span>
                    </p>
                </div>
            </form>
        </div>
    </body>
</html>