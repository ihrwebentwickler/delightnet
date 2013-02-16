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

use delightnet\delightos\Registry;
use delightnet\delightos\Request;
use \DirectoryIterator;

class Blogger {

    public $pluginNb;
    public $Mandn;
    public $Filehandle;
    public $objectBlogger;
    public $bloggerTemplate;
    public $partTemplate;

    public function __construct($configuration) {
        $this->objectBlogger = $configuration;
        $this->bloggerTemplate = Registry::get('Filehandle')->readFilecontent("public/extensions/Blogger/template/Blogger.tpl");
        $this->partTemplate = Registry::get('Filehandle')->readFilecontent("public/extensions/Blogger/template/parts/Blogger.tpl");
    }

    public function startBlogger($pluginnb) {
        $strPart = '';
        (string) $pluginnb;
        if (sizeof($this->objectBlogger->Blogger->blogEntries->$pluginnb) > 0) {
            foreach ($this->objectBlogger->Blogger->blogEntries->$pluginnb as $key => $objBlog) {
                $strPart .= $this->partTemplate;
                $strPart = Registry::get('Mandn')->setBlock($strPart, "BLOGGER_HEADER", $this->objectBlogger->Blogger->blogEntries->$pluginnb->$key->header);
                $strPart = Registry::get('Mandn')->setBlock($strPart, "BLOGGER_TEXT", $this->objectBlogger->Blogger->blogEntries->$pluginnb->$key->text);
            }
        }
        
        $strBlogger = $this->bloggerTemplate;
        $strBlogger = Registry::get('Mandn')->setBlock($strBlogger, "BLOGGER_BLOGTITLE", $this->objectBlogger->Blogger->Blogger->$pluginnb->blogtitle);
        $strBlogger = Registry::get('Mandn')->setBlock($strBlogger, "BLOGGER_CONTENT", $strPart);

        return $strBlogger;
    }

}