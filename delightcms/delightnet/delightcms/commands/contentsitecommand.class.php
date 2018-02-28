<?php
/*
 * The Handling of CMS-Admin - Choise Content-site to Edit
 * 
 * @author    Gunnar von Spreckelsen <service@ihrwebentwickler.de>
 * @package   delightcms
 * @version   3.11
 * 
 */

namespace delightnet\delightcms\commands;

use delightnet\delightcms\CmsTemplateView;
use delightnet\delightcms\CmsTemplate;

class contentSiteCommand extends CmsTemplate implements CmsTemplateView {

    public function execute() {
        if ($this->getandSetSessionAndCmdEnvirement($this->Request->getParameter('cmd'), $this->Request->getParameter('user'), $this->Request->getParameter('password')) === true) {
            $this->buildCmsTemplate($this->Request);

            if (file_exists("../public/" . $this->Request->getParameter('choisedSite'))) {
                $strSiteFile = $this->Filehandle->readFilecontent("../public/" . $this->Request->getParameter('choisedSite'));
                $strHiddenFilename = $this->Request->getParameter('choisedSite');
            } else {
                $strSiteFile = "";
                $strHiddenFilename = "";
            }

            $this->template = $this->Mandn->setBlock($this->template, "DATA_SITEFILE", $strSiteFile);
            $this->template = $this->Mandn->setBlock($this->template, "LINK_SITEFILE", $strHiddenFilename);
        } else {
            $this->readAndReplaceLoginSite();
        }

        $this->Response->write($this->template);
    }

}
?>