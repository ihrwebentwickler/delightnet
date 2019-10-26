<!DOCTYPE html>
<html lang="{CURRENT_LANG}">
<!--
This website is powered by DelightOS 3
Enjoy for unlimited creativity!
-->
<head>
    <meta charset="UTF-8">

    <title>{SITETITLE}</title>
    <meta name="description" content="{DESCRIPTION}">
    <meta name="author" content="Tura Hechthausen">
    <meta name="viewport" content="width=device-width,initial-scale=1"/>

    <link rel="shortcut icon" href="public/images/favicon.ico">
    <link rel="apple-touch-icon-precomposed" href="public/images/apple-touch-icon-precomposed.png">
    <link rel="stylesheet" href="public/themes/maintheme/css/main.css"/>

    {DYNAMICFILEINTEGRATION}

    <script>
        "use strict";

        // js global env
        var GLOBAL = GLOBAL || {};
        GLOBAL.ENV = GLOBAL.ENV || {};
        GLOBAL.STATUS = GLOBAL.STATUS || {};

        GLOBAL.ENV = {
            "os": "{OS}",
            "browser": "{BROWSER}",
            "version": {VERSION},
            "isDevice": {IS_MOBILE_DEVICE},
        };

        // status-infos
        GLOBAL.STATUS = {
            "currentCommand": "{CURRENT_COMMAND}",
            "lang": "{CURRENT_LANG}"
        }
    </script>

    {MULTIDESIGN}
</head>
<body>
    <header>
        <h1>{SITETITLE}</h1>
    </header>
    <section id="page1" data-role="page" data-theme="a" data-content-theme="a">
        <section id="navigation">
            <nav id="mainNavigation" data-role="navbar">
                <ul>
                    {LOOP MENU0}
                        <li><a href="/{FILENAME}.html" title="{SITE}" {ACTIV} data-role="button">{SITE}</a></li>
                    {/LOOP}
                </ul>
            </nav>
            <div id="pluginNavigation">
                <select name="menu2" size="1"
                        onchange="javascript:self.location.href = this.options[this.selectedIndex].title + '.html'"
                        class="ui-selectmenu-list ui-listview">
                    <option title="pluginwahl">Auswahl Plugin:</option>
                    {LOOP MENU1}
                        <option title="{FILENAME}" {ACTIV}>{SITE}</option>
                    {/LOOP}
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
</body>
</html>