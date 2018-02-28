<?php

/*
 * default-handler for dialog-overlays, write CSS-styles of dialog-window
 * 
 * @author    Gunnar von Spreckelsen <service@ihrwebentwickler.de>
 * @package   delightcms
 * @version   3.11
 * 
 */

namespace delightnet\delightcms\commands;

use delightnet\delightcms\CmsTemplateView;
use delightnet\delightcms\CmsTemplate;

class dialogeditorCommand extends CmsTemplate implements CmsTemplateView {

    public function execute() {
        if ($this->getandSetSessionAndCmdEnvirement($this->Request->getParameter('cmd'), $this->Request->getParameter('user'), $this->Request->getParameter('password')) === true) {
            if (
                    $this->Request->getParameter('submitDialogForm')
                    && $this->Request->getParameter('filelink') != ""
                    && $this->Request->getParameter('strTextarea') != ""
                    && $this->Request->getParameter('DialogContentSite') != ""
            ) {
                $strFileTest = (file_exists($this->Request->getParameter('filelink'))) ? $this->Filehandle->readFilecontent($this->Request->getParameter('filelink')) : "";

                if ($strFileTest != "") {
                    $this->Filehandle->writeFilecontent($this->Request->getParameter('filelink'), $this->Request->getParameter('strTextarea'));
                }

                $this->Response->addHeader("location", $this->Request->getParameter('DialogContentSite') . ".html");
            }

            $this->Response->write($this->DynamicSite);
        } else {
            $this->readAndReplaceLoginSite();
            $this->Response->write($this->template);
        }
    }

}