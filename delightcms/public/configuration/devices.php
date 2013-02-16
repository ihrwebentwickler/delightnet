<?php
$devices['os'] = array
(
	'Macintosh'     => array('os'=>'mac',        'mobile'=>false),
	'Windows CE'    => array('os'=>'win-ce',     'mobile'=>true),
	'Windows Phone' => array('os'=>'win-ce',     'mobile'=>true),
	'Windows'       => array('os'=>'win',        'mobile'=>false),
	'iPad'          => array('os'=>'ios',        'mobile'=>true),
	'iPhone'        => array('os'=>'ios',        'mobile'=>true),
	'iPod'          => array('os'=>'ios',        'mobile'=>true),
	'Android'       => array('os'=>'android',    'mobile'=>true),
	'Blackberry'    => array('os'=>'blackberry', 'mobile'=>true),
	'Symbian'       => array('os'=>'symbian',    'mobile'=>true),
	'WebOS'         => array('os'=>'webos',      'mobile'=>true),
	'Linux'         => array('os'=>'unix',       'mobile'=>false),
	'FreeBSD'       => array('os'=>'unix',       'mobile'=>false),
	'OpenBSD'       => array('os'=>'unix',       'mobile'=>false),
	'NetBSD'        => array('os'=>'unix',       'mobile'=>false),
);


/**
 * Browsers (check OmniWeb before Safari and Opera Mini/Mobi before Opera!)
 */
$devices['browser'] = array
(
	'MSIE'       => array('browser'=>'ie',           'shorty'=>'ie', 'version'=>'/^.*?MSIE (\d+(\.\d+)*).*$/'),
	'Firefox'    => array('browser'=>'firefox',      'shorty'=>'fx', 'version'=>'/^.*Firefox\/(\d+(\.\d+)*).*$/'),
	'Chrome'     => array('browser'=>'chrome',       'shorty'=>'ch', 'version'=>'/^.*Chrome\/(\d+(\.\d+)*).*$/'),
	'OmniWeb'    => array('browser'=>'omniweb',      'shorty'=>'ow', 'version'=>'/^.*Version\/(\d+(\.\d+)*).*$/'),
	'Safari'     => array('browser'=>'safari',       'shorty'=>'sf', 'version'=>'/^.*Version\/(\d+(\.\d+)*).*$/'),
	'Opera Mini' => array('browser'=>'opera-mini',   'shorty'=>'oi', 'version'=>'/^.*Opera Mini\/(\d+(\.\d+)*).*$/'),
	'Opera Mobi' => array('browser'=>'opera-mobile', 'shorty'=>'om', 'version'=>'/^.*Version\/(\d+(\.\d+)*).*$/'),
	'Opera'      => array('browser'=>'opera',        'shorty'=>'op', 'version'=>'/^.*Version\/(\d+(\.\d+)*).*$/'),
	'IEMobile'   => array('browser'=>'ie-mobile',    'shorty'=>'im', 'version'=>'/^.*IEMobile (\d+(\.\d+)*).*$/'),
	'BlackBerry' => array('browser'=>'blackberry',   'shorty'=>'ca', 'version'=>'/^.*BlackBerry\/(\d+(\.\d+)*).*$/'),
	'Konqueror'  => array('browser'=>'konqueror',    'shorty'=>'ko', 'version'=>'/^.*Konqueror\/(\d+(\.\d+)*).*$/')
);