<?php
/* index.php
 * frontcontroller start website-env
 * The index.php in the root-folder serves as start-point into frontend-applikation.
 *
 * avaible option:
 * error_reporting(E_ALL)  use error_reporting(0); to turn off showing erros in productiv-env
 *
 * @author Gunnar von Spreckelsen <service@ihrwebentwickler.de>
 * @version 5.1 (02.03.2014)
 */

// error_reporting(0)
error_reporting(E_ALL);

// Register Autoload, set include path start whole output-passage
require_once "delightnet/initLoader.php";