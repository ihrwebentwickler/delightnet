<?php
/*
 * Loading, handling and saving Ini-Data-Files
 * 
 * @author    Gunnar von Spreckelsen <service@ihrwebentwickler.de>
 * @package   delightcms
 * @version   3.11
 * 
 */

namespace delightnet\delightcms\commands;

use delightnet\delightcms\CmsTemplateView;
use delightnet\delightcms\CmsTemplate;

class fileStreamLoaderCommand extends CmsTemplate implements CmsTemplateView {

    public function execute() {

        if ($this->getandSetSessionAndCmdEnvirement($this->Request->getParameter('cmd'), $this->Request->getParameter('user'), $this->Request->getParameter('password')) === true) {
            $this->buildCmsTemplate($this->Request);

            switch ($this->cmd) {
                case "css":
                    $filelink = "css/main.css";
                    break;
                case "webdata":
                    $filelink = "configuration/main.ini";
                    break;
                case "contactformdata":
                    $filelink = "configuration/contactform.ini";
                    break;
                default:
                    $filelink = "";
                    break;
            }

            if ($this->Request->getParameter('textarea1') != null && $filelink != "") {
                $this->Filehandle->writeFilecontent("../public/" . $filelink, $this->Request->getParameter('textarea1'));
                $this->replaceSaveConfirmation(true);
            } else {
                $this->replaceSaveConfirmation(false);
            }

            $strCss = $this->Filehandle->readFilecontent("../public/" . $filelink);
            $this->template = $this->Mandn->setBlock($this->template, "DATA_CSSFILE", $strCss);
        } else {
            $this->readAndReplaceLoginSite();
        }

        $this->Response->write($this->template);
    }

}
?>