<?php

/*
 * read and write simple textarea to handle extensionlist
 * 
 * @author    Gunnar von Spreckelsen <service@ihrwebentwickler.de>
 * @package   delightcms
 * @version   3.11
 * 
 */

namespace delightnet\delightcms\commands;

use delightnet\delightcms\CmsTemplateView;
use delightnet\delightcms\CmsTemplate;

class inieditCommand extends CmsTemplate implements CmsTemplateView {

    public function execute() {
        if ($this->getandSetSessionAndCmdEnvirement($this->Request->getParameter('cmd'), $this->Request->getParameter('user'), $this->Request->getParameter('password')) === true) {
            $this->buildCmsTemplate($this->Request);

            switch ($this->Request->getParameter('action')) {
                case "main":
                    $filelink = "../public/configuration/main.ini";
                    break;
                case "extensions":
                    $filelink = "../delightnet/extensions/extensions.ini";
                    break;
                case "Contact":
                    $filelink = "../delightnet/extensions/Contact/public/configuration/Contact.ini";
                    break;
                default:
                    $filelink = false;
            }

            if ($filelink) {
                if (file_exists($filelink)) {
                    if ($this->Request->getParameter('action_extlist') && $this->Request->getParameter('strTextarea') != "" && $this->Request->getParameter('action') != "") {
                        $strIni = $this->Filehandle->cleanIniStr($this->Request->getParameter('strTextarea'));
                        $this->Filehandle->writeFilecontent($filelink, $strIni);
                        $this->replaceSaveConfirmation(true);
                    } else {
                        $this->replaceSaveConfirmation(false);
                    }

                    $this->template = $this->Mandn->setBlock($this->template, "EXTCONTENT", $this->Filehandle->readFilecontent($filelink));
                }
            }
            
            $this->template = $this->Mandn->setBlock($this->template, "NAME_SYSTEMEXT", $this->Request->getParameter('action'));
        } else {
            $this->readAndReplaceLoginSite();
        }

        $this->Response->write($this->template);
    }
}