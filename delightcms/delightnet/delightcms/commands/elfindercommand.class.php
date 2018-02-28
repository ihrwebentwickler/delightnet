<?php

/*
 * handling elfinder Image - Fileexplorer
 * 
 * @author    Gunnar von Spreckelsen <service@ihrwebentwickler.de>
 * @package   delightcms
 * @version   3.11
 * 
 */

namespace delightnet\delightcms\commands;

use delightnet\delightcms\CmsTemplateView;
use delightnet\delightcms\CmsTemplate;

class elFinderCommand extends CmsTemplate implements CmsTemplateView {

    public function execute() {
        if ($this->getandSetSessionAndCmdEnvirement($this->Request->getParameter('cmd'), $this->Request->getParameter('user'), $this->Request->getParameter('password')) === true) {
            $this->readDynamicCmsContentFile();
            $this->Response->write($this->DynamicSite);
        } else {
            $this->readAndReplaceLoginSite();
            $this->Response->write($this->template);
        }
    }

}