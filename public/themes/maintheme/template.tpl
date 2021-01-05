<!DOCTYPE html>
<!--
This website is powered by DelightCMS 4
Enjoy for unlimited creativity!
-->
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{SITETITLE}</title>
    <meta name="description" content="{DESCRIPTION}">

    <!-- Theme CSS -->
    <link rel="stylesheet" href="/public/themes/maintheme/css/style.css">
    <!-- Responsive CSS -->
    <link rel="stylesheet" href="/public/themes/maintheme/css/theme-responsive.css">
    <!-- Favicons -->
    <link rel="shortcut icon" href="/public/images/favicon.ico">
    <link rel="apple-touch-icon" href="/public/images/apple-touch-icon.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/public/images/apple-touch-icon-72.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/public/images/apple-touch-icon-114.png">

    {DYNAMICFILEINTEGRATION}

    <script>
        "use strict";

        // js global env
        var GLOBAL = GLOBAL || {};
        GLOBAL.STATUS = GLOBAL.STATUS || {};
        GLOBAL.CONFIG = GLOBAL.CONFIG || {};

        // global configuration
        GLOBAL.CONFIG = {
            "isMobileDevice": "{IS_MOBILE_DEVICE}"
        }
        // status-infos
        GLOBAL.STATUS = {
            "currentCommand": "{CURRENT_COMMAND}",
            "lang": "{CURRENT_LANG}"
        }
    </script>
</head>
<body>
<div class="content-wrapper">
    <!-- Site header BEGIN -->
    <header>
        <ul id="menu">
            <li class="logo">
                <a href="/home.html">
                    <img src="/public/images/logo.png" alt="Ihr Webentwickler">
                </a>
            </li>
            <li class="dropdown">
                <a>
                    {L:OFFERTS}<i class="material-icons">keyboard_arrow_down</i>
                </a>
                <div class="dropdown-menu display-none">
                    <div class="content-box-orangeborder">
                        {LOOP MENU1}
                            <a class="{MENU_ACTIV}" href="/{FILENAME}.html">{SITE}</a>
                        {/LOOP}
                    </div>
                </div>
            </li>
            {LOOP MENU2}
                <li class="menu-links">
                    <a class="{MENU_ACTIV}" href="/{FILENAME}.html">{SITE}</a>
                </li>
            {/LOOP}
            <li class="lang-navigation">
                <div>
                    {LANGNAVIGATION}
                </div>
            </li>
            <li class="additional-media">
                <a href="https://www.youtube.com" target="_blank">
                    <span class="ti-youtube"></span>
                </a>
                <a href="https://github.com" target="_blank">
                    <span class="ti-github"></span>
                </a>
            </li>
        </ul>
    </header>
    <!-- Site header END -->
    <!-- breadcrumbs section BEGIN -->
    <div id="breadcrumbs">
        <span>&gt;&gt;&nbsp;</span>
        <span>{L:PLACE_HOME}:</span>
        <span>&nbsp;</span>
        <span><a href="/{CURRENT_COMMAND}.html">{BREADCRUMB}</a></span>
    </div>
    <!-- breadcrumbs section END -->
    <!-- Modal mobile aside-navigation BEGIN-->
    <div class="device-wrapper">
        <ul class="device-header">
            <li><span class="device-dehaze"><i class="material-icons">dehaze</i></span></li>
            <li><a href="home.html">Ihr LOGO-TEXT</a></li>
            <li>
                <a href="https://www.youtube.com" target="_blank">
                    <span class="ti-youtube"></span>
                </a>
                <a href="https://github.com" target="_blank">
                    <span class="ti-github"></span>
                </a>
            </li>
            <li class="device-lang-navigation">
                <div>
                    {LANGNAVIGATION}
                </div>
            </li>
        </ul>
        <div class="device-menu">
            <ul class="IE-Firefox-hide-scrollbar">
                {LOOP MENU1-DEVICE}
                    <li>
                        <a href="/{FILENAME}.html" class="{MENU_ACTIV_DEVICE}">{L:OFFERTS}&nbsp;<span
                                    class="material-icons">double_arrow</span><span>&nbsp;</span>{SITE}
                        </a>
                    </li>
                {/LOOP}
                {LOOP MENU2-DEVICE}
                    <li>
                        <a class="{MENU_ACTIV_DEVICE}" href="/{FILENAME}.html">{SITE}</a>
                    </li>
                {/LOOP}
            </ul>
        </div>
    </div>
    <!-- Modal mobile aside-navigation END -->
    <!-- content section -->
    <div id="content">
        {CONTENT}
    </div>
</div>
</body>
</html>
