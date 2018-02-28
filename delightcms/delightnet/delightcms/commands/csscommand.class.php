<?php

/*
 * The Handling of loading filenames from css-folders and playout of css-boxes
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

class cssCommand extends CmsTemplate implements CmsTemplateView {

    public function execute() {
        if ($this->getandSetSessionAndCmdEnvirement($this->Request->getParameter('cmd'), $this->Request->getParameter('user'), $this->Request->getParameter('password')) === true) {
            $this->buildCmsTemplate($this->Request);
            
            $arrayCssFoldersOfWebsite =
                    array(
                        "../public/css" => array(
                            "link" => "../public/css"
                        ),
                        "../public/css/devices" => array(
                            "link" => "../public/css/devices"
                        )
            );

            $this->template = $this->Mandn->setBlock($this->template, "CONTENT_CSSHELPER1", $this->Gui->getSelectBoxOfEditableFiles(null, $arrayCssFoldersOfWebsite, 0));

            // $arrayCssFoldersOfDevices = array();
            foreach ($this->arrayExtensions as $class => $arrayExt) {
                if ($arrayExt['active'] == 1 && file_exists("../public/extensions/" . $class . "/css/" . $class . ".css")) {
                    $arrayCssFoldersOfDevices["../public/extensions/" . $class . "/css/"]["link"] = "../public/extensions/" . $class . "/css/";
                }
            }
            
            $this->template = $this->Mandn->setBlock($this->template, "CONTENT_CSSHELPER2", $this->Gui->getSelectBoxOfEditableFiles(null, $arrayCssFoldersOfDevices, 0));
            $this->template = $this->Gui->writeStandardDialogEditor($this->template);
        } else {
            $this->readAndReplaceLoginSite();
        }
        $this->template = $this->Mandn->setBlock($this->template, "PASSWORDERROR", "");
        $this->Response->write($this->template);
    }

}