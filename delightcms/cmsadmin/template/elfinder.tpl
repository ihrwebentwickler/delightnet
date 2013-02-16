<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">

    <head>
        <meta name="language" content="german, de, deutsch" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

        <link rel="stylesheet" type="text/css" href="../../../cmsadmin/css/main.css" />
        <link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/ui-lightness/jquery-ui.css" />
        <link rel="stylesheet" href="../../../cmsadmin/extension/elfinder-1.2/css/elfinder.css" />

        <script  type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script> 
        <script  type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js"></script>
        <script  type="text/javascript" src="../../../cmsadmin/extension/elfinder-1.2/js/elfinder.min.js"></script>
        <script  type="text/javascript" src="../../../cmsadmin/extension/elfinder-1.2/js/i18n/elfinder.de.js"></script>

        <link rel="shortcut icon" href="favicon.ico" />

        <meta name="author" content="Ihr Webentwickler,Gunnar von Spreckelsen" />
        <meta name="robots" content="noindex, nofollow" />

        <meta name="expires" content="never" />
        <meta name="description" content="Inhalts-Verwaltung DelightCms" />

        <script type="text/javascript" charset="utf-8">
            $().ready(function() {
                var funcNum = window.location.search.replace(/^.*CKEditorFuncNum=(\d+).*$/, "$1");
 
                $('#finder').elfinder({
                    url : '../../../cmsadmin/extension/elfinder-1.2/connectors/php/connectorCkeditor.php',
                    lang : 'de',
                    editorCallback : function(url) {
                        window.opener.CKEDITOR.tools.callFunction(funcNum, url);
                        window.close();
                    },
         
                    docked : true,

                    dialog : {
                        title : 'DelightCMS uses Elfinder',
                        height : 380
                    }
                })
			
                $('#dock,#undock').click(function() {
                    $('#finder').elfinder($(this).attr('id'));
                })
			
            })
        </script>

    </head>
    <body>

        <div id="dock">andocken</div><div id="undock">als Fenster</div>
        <div id="finder">finder</div>

    </body>
</html>