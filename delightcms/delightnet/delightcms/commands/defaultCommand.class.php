<?php

/*
 * The Admin-CMS-Default Handler
 * 
 * @author    Gunnar von Spreckelsen <service@ihrwebentwickler.de>
 * @package   delightcms
 * @version   3.11
 * 
 */

namespace delightnet\delightcms\commands;

use delightnet\delightcms\CmsTemplateView;
use delightnet\delightcms\CmsTemplate;
use delightnet\delightcms\Gui;

class defaultCommand extends CmsTemplate implements CmsTemplateView {

    public function execute() {
        if ($this->getandSetSessionAndCmdEnvirement($this->Request->getParameter('cmd'), $this->Request->getParameter('user'), $this->Request->getParameter('password')) === true) {
            $this->buildCmsTemplate();
        } else {                    
            $this->readAndReplaceLoginSite();
        }

        $this->template = $this->Gui->writeStandardDialogEditor($this->template);
        $this->Response->write($this->template);
    }

}