<?php

/*
 * dynamic load configuration-site of extension
 * 
 * @author    Gunnar von Spreckelsen <service@ihrwebentwickler.de>
 * @package   delightcms
 * @version   1.00
 * 
 */

namespace delightnet\delightcms\commands;

use delightnet\delightcms\CmsTemplateView;
use delightnet\delightcms\CmsTemplate;
use delightnet\delightcms\Gui;

class extconfCommand extends CmsTemplate implements CmsTemplateView {

    public function execute() {
        if ($this->getandSetSessionAndCmdEnvirement($this->Request->getParameter('cmd'), $this->Request->getParameter('user'), $this->Request->getParameter('password')) === true) {
            $strExtconf = "";
            $extMenuName = (isset($this->arrayExtensions[$this->extSystemName]['menuname'])) ? $this->arrayExtensions[$this->extSystemName]['menuname'] : null;

            // save data if submitted
            if ($this->Request->getParameter('submitExt') && $this->extSystemName != ""
                    && $extMenuName != null && sizeof($this->arrayExtensions[$this->extSystemName]) > 0) {
                $isSaveError = true;
                $filelinkExt = "";

                switch ($this->extSystemName) {
                    default:
                        $filelinkExt = "../public/extensions/" . $this->extSystemName . "/configuration/" . $this->extSystemName . ".json";
                }

                if (file_exists($filelinkExt)) {
                    $strJson = $this->Gui->jsonDecode($this->Request->getParameter($this->extSystemName));
                    $strJson = '\{"' . $this->extSystemName . '": ' . $strJson . '\}';
                    $strJson = $this->Filehandle->cleanJsonStr($strJson);
                    $this->Filehandle->writeFilecontent($filelinkExt, $strJson);
                    $isSaveError = false;
                } else {
                    $isSaveError = true;
                }

                $this->Request->loadNewHeaderLocation(($isSaveError === false) ? "savemessage" : "extsaveerror");
                exit();
            } else {
                // Build CMS-Data
                $this->buildCmsTemplate();
                if ($this->extSystemName != "") {
                    $strExtconf = $this->Gui->replaceDomDataInExtTemplate($this->extSystemName);
                }

                $this->template = $this->Mandn->setBlock($this->template, "EXTCONFCONTENT", $strExtconf);
                $this->Response->write($this->template);
            }
        } else {
            $this->readAndReplaceLoginSite();
        }
    }

}