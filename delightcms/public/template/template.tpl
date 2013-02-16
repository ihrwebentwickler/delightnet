<!DOCTYPE html>
<html lang="de">
    <!--
    This website is powered by DelightCMS 0.90
    Enjoy for unlimited creativity!
    -->
    <head>
        <meta charset="UTF-8">

        <title>{SITETITLE}</title>
        <meta name="google-site-verification" content="">
        <meta name="author" content="Your Name Here">
        <meta name="viewport" content="width=device-width,initial-scale=1" />
        {GOOGLESITEVERIFICATION}

        <link rel="shortcut icon" href="public/images/favicon.ico">
        <link rel="apple-touch-icon-precomposed" href="public/images/apple-touch-icon-precomposed.png">

        <link rel="stylesheet" href="public/css/main.css" />
        <link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/ui-lightness/jquery-ui.css" />

        <script>
            // js global env
            var global = {
                "os" : "{STR_OS}",
                "browser" : "{STR_BROWSER}",
                "version" : "{STR_VERSION}",
                "currentCommand" : "{STR_CURRENT_COMMAND}"    
            };
            {OUTPUT_SYSTEMINFO}
        
            // namespace symbol
            var delightcms = {};
        
            // plugin mediadirectplayer namespace
            delightcms.mediadirectplayer = {};
        </script>        

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
        <script src="/public/js/jquery.session-20.js"></script>
        <script src="public/js/main.js"></script>
        {DYNAMICFILEINTEGRATION}
        {MULTIDESIGN}
    </head>
    <body>
        <header>
            <hgroup>
                <h1>{SITETITLE}</h1>
            </hgroup>
        </header>
        <section id="page1" data-role="page" data-theme="a" data-content-theme="a">
            <section id="navigation">
                <nav id="mainNavigation" data-role="navbar">
                    <ul>
                        {LOOP menu1}
                        <li><a href="/{FILENAME}.html" title="{SITE}" {MENU_ACTIV} data-role="button">{SITE}</a></li>
                        {/LOOP menu1}
                    </ul>
                </nav>
                <div id="pluginNavigation"> 
                    <select name="menu2" size="1" onchange="javascript:self.location.href = this.options[this.selectedIndex].title + '.html'" class="ui-selectmenu-list ui-listview">
                        <option title="pluginwahl">Auswahl Plugin:</option>
                        {LOOP menu2}
                        <option title="{FILENAME}" {MENU_ACTIV}>{SITE}</option>
                        {/LOOP menu2}
                    </select>
                </div>
                <div id="languageNavigation">
                    {LANGNAVIGATION}
                </div>
                <div class="clear">
                </div>
            </section>
            <section>
                <article>
                    <section id="content">
                        {CONTENT}
                    </section>
                </article>
            </section>
            <footer>
                <small>Copyright Your Name Here 2012. All Rights Reserved.</small>
            </footer>
        </section>

        {GOOGLEANALYTICS}
    </body>
</html>