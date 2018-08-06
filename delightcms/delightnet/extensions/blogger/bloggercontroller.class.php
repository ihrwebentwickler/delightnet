<?php

/*
 * Blogger, a simple-Blog-System,
 * 
 * @author    Gunnar von Spreckelsen <service@ihrwebentwickler.de>
 * @package   extension
 * @version   1.10
 * 
 */

namespace delightnet\extensions\Blogger;
use delightnet\delightos\Controller;

class BloggerController extends Controller {

    public function action() {
        var_dump($this->objConfiguration);
        // $this->strExtTemplate = $this->MandN->replaceLoopContent($this->strExtTemplate, "BLOGGER1", $this->cmd, "FILENAME", $arrayMenuDevice);
        return $this->strExtTemplate;
    }
}