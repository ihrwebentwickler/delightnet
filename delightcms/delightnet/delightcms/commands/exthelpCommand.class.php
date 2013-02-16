<?php

/*
 * dynamic load help-site of extension
 * 
 * @author    Gunnar von Spreckelsen <service@ihrwebentwickler.de>
 * @package   delightcms
 * @version   1.00
 * 
 */

namespace delightnet\delightcms\commands;

use delightnet\delightcms\CmsTemplateView;
use delightnet\delightcms\CmsTemplate;

class exthelpCommand extends CmsTemplate implements CmsTemplateView {

    public function execute() {
        if ($this->getandSetSessionAndCmdEnvirement($this->Request->getParameter('cmd'), $this->Request->getParameter('user'), $this->Request->getParameter('password')) === true) {
            $this->buildCmsTemplate($this->Request);
            $strExthelp = '';
            
            if (file_exists("../delightnet/extensions/" . $this->Request->getParameter('extSystemName') . "/cmsadmin/help.tpl")) {
               $strExthelp = $this->Filehandle->readFilecontent("../delightnet/extensions/" . $this->Request->getParameter('extSystemName') . "/cmsadmin/help.tpl"); 
            }
            elseif (file_exists("../cmsadmin/template/parts/exthelp.tpl")) {
                $strExthelp = $this->Filehandle->readFilecontent("../cmsadmin/template/parts/exthelp.tpl"); 
            }

            $this->template = $this->Mandn->setBlock($this->template, "EXTHELPCONTENT", $strExthelp);
        } else {
            $this->readAndReplaceLoginSite();
        }

        $this->Response->write($this->template);
    }

}