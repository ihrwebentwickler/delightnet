<?php

/*
 * building Zip-File-Archiv for Data-Backup
 * 
 * @author    Gunnar von Spreckelsen <service@ihrwebentwickler.de>
 * @package   delightcms
 * @version   3.50
 * 
 */

namespace delightnet\delightcms\commands;

use delightnet\delightcms\CmsTemplateView;
use delightnet\delightcms\CmsTemplate;

class webexportCommand extends CmsTemplate implements CmsTemplateView {

    public function execute() {
        if ($this->getandSetSessionAndCmdEnvirement($this->Request->getParameter('cmd'), $this->Request->getParameter('user'), $this->Request->getParameter('password')) === true) {
            $this->buildCmsTemplate($this->Request);

            if ($this->Request->getParameter('action_public') == "Lokal sichern") {
                $archivFileName = $this->Request->getServerName() . "_DS.zip";
                $arrayFolders[0] = "../public";

                $this->Filehandle->getZipArchiv($archivFileName, $arrayFolders);
            }
        } else {
            $this->readAndReplaceLoginSite();
        }

        $this->Response->write($this->template);
    }

}