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
use delightnet\delightos\Filehandle;

class languageCommand extends CmsTemplate implements CmsTemplateView {

    public function execute() {
        if ($this->getandSetSessionAndCmdEnvirement($this->Request->getParameter('cmd'), $this->Request->getParameter('user'), $this->Request->getParameter('password')) === true) {
            if ($this->Request->getParameter('action')) {
                if (isset($this->arrayLang['defaults']['currentLanguage'])) {
                    $this->arrayLang['defaults']['currentLanguage'] = $this->Request->getParameter('action') + 1 - 1;
                }

                $this->Filehandle->writeSimpleIniFile("../cmsadmin/configuration/lang/lang.ini", $this->arrayLang);

                $this->buildCmsTemplate();
                
                $replaceLangImgToDisplayNone = '';
                foreach ($this->arrayLang['lang'] as $langKey => $isoCountryShortcut) {
                    $replaceLangImgToDisplayNone = ($langKey == $this->arrayLang['defaults']['currentLanguage']) ? "" : "displayNone";
                    $this->template = $this->Mandn->setBlock($this->template, "DISPLAY_NONE_" . strtoupper($isoCountryShortcut), $replaceLangImgToDisplayNone);
                }
            }
        } else {
            $this->readAndReplaceLoginSite();
        }

        $this->Response->write($this->template);
    }

}