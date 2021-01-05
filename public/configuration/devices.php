<?php
$devices['os'] = array
(
    'Macintosh' => array('os' => 'mac', 'isDevice' => 'false'),
    'Windows CE' => array('os' => 'win-ce', 'isDevice' => 'true'),
    'Windows Phone' => array('os' => 'win-ce', 'isDevice' => 'true'),
    'Windows' => array('os' => 'win', 'isDevice' => 'false'),
    'iPad' => array('os' => 'ios', 'isDevice' => 'true'),
    'iPhone' => array('os' => 'ios', 'isDevice' => 'true'),
    'iPod' => array('os' => 'ios', 'isDevice' => 'true'),
    'Android' => array('os' => 'android', 'isDevice' => 'true'),
    'Blackberry' => array('os' => 'blackberry', 'isDevice' => 'true'),
    'Symbian' => array('os' => 'symbian', 'isDevice' => 'true'),
    'WebOS' => array('os' => 'webos', 'isDevice' => 'true'),
    'Linux' => array('os' => 'unix', 'isDevice' => 'false'),
    'FreeBSD' => array('os' => 'unix', 'isDevice' => 'false'),
    'OpenBSD' => array('os' => 'unix', 'isDevice' => 'false'),
    'NetBSD' => array('os' => 'unix', 'isDevice' => 'false'),
);

/**
 * Browsers (check OmniWeb before Safari and Opera Mini/Mobi before Opera!)
 */
$devices['browser'] = array
(
    'MSIE' => array('browser' => 'ie', 'shorty' => 'ie', 'version' => '/^.*?MSIE (\d+(\.\d+)*).*$/','minVersionHtml5Media' => 9,'playerCodeHtml5Media' => 2),
    'Firefox' => array('browser' => 'firefox', 'shorty' => 'fx', 'version' => '/^.*Firefox\/(\d+(\.\d+)*).*$/','minVersionHtml5Media' => 4,'playerCodeHtml5Media' => 1),
    'Chrome' => array('browser' => 'chrome', 'shorty' => 'ch', 'version' => '/^.*Chrome\/(\d+(\.\d+)*).*$/','minVersionHtml5Media' => 6,'playerCodeHtml5Media' => 1),
    'OmniWeb' => array('browser' => 'omniweb', 'shorty' => 'ow', 'version' => '/^.*Version\/(\d+(\.\d+)*).*$/','minVersionHtml5Media' => 3,'playerCodeHtml5Media' => 2),
    'Safari' => array('browser' => 'safari', 'shorty' => 'sf', 'version' => '/^.*Version\/(\d+(\.\d+)*).*$/','minVersionHtml5Media' => 3,'playerCodeHtml5Media' => 2),
    'Opera Mini' => array('browser' => 'opera-mini', 'shorty' => 'oi', 'version' => '/^.*Opera Mini\/(\d+(\.\d+)*).*$/','minVersionHtml5Media' => 11.1,'playerCodeHtml5Media' => 2),
    'Opera Mobi' => array('browser' => 'opera-mobile', 'shorty' => 'om', 'version' => '/^.*Version\/(\d+(\.\d+)*).*$/','minVersionHtml5Media' => 11.1,'playerCodeHtml5Media' => 2),
    'Opera' => array('browser' => 'opera', 'shorty' => 'op', 'version' => '/^.*Version\/(\d+(\.\d+)*).*$/','minVersionHtml5Media' => 10.6,'playerCodeHtml5Media' => 1),
    'IEMobile' => array('browser' => 'ie-mobile', 'shorty' => 'im', 'version' => '/^.*IEMobile (\d+(\.\d+)*).*$/','minVersionHtml5Media' => 9,'playerCodeHtml5Media' => 2),
    'BlackBerry' => array('browser' => 'blackberry', 'shorty' => 'ca', 'version' => '/^.*BlackBerry\/(\d+(\.\d+)*).*$/','minVersionHtml5Media' => 6,'playerCodeHtml5Media' => 2),
    'Konqueror' => array('browser' => 'konqueror', 'shorty' => 'ko', 'version' => '/^.*Konqueror\/(\d+(\.\d+)*).*$/','minVersionHtml5Media' => 3,'playerCodeHtml5Media' => 2)
);