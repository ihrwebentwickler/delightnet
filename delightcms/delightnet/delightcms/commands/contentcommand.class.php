<?php

/*
 * The Handling of CMS-Admin - Edit Page Content
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

class contentCommand extends CmsTemplate implements CmsTemplateView {
     /**
     * execute loading the content-
     *  
     * @param string $cmd
     * @param string $user
     * @param string $password
     * @return \stdClass
     */
    public function execute() {
        if ($this->getandSetSessionAndCmdEnvirement($this->Request->getParameter('cmd'), $this->Request->getParameter('user'), $this->Request->getParameter('password')) === true) {
            $this->buildCmsTemplate($this->Request);

            $arrayContent1 =
                    array(
                        "../public/template" => array(
                            "link" => "../public/template"
                        )
            );

            $arrayContent2 =
                    array(
                        "../public/template/parts" => array(
                            "link" => "../public/template/parts"
                        )
            );

            foreach ($this->arrayExtensions as $class => $arrayExt) {
                if ($arrayExt['active'] == 1 && is_dir("../public/extensions/" . $class . "/template")) {
                    $arrayContent3["../public/extensions/" . $class . "/template"]["link"] = "../public/extensions/" . $class . "/template";
                }
            }

            foreach ($this->arrayExtensions as $class => $arrayExt) {
                if ($arrayExt['active'] == 1 && is_dir("../public/extensions/" . $class . "/template/parts")) {
                    $arrayContent4["../public/extensions/" . $class . "/template/parts"]["link"] = "../public/extensions/" . $class . "/template/parts";
                }
            }

            $arrayBlockedSitesStandardPages[0] = "template.tpl";
            $this->template = $this->Mandn->setBlock($this->template, "CONTENT_TPLHELPER1", $this->Gui->getSelectBoxOfEditableFiles($arrayBlockedSitesStandardPages, $arrayContent1, 1));
            $this->template = $this->Mandn->setBlock($this->template, "CONTENT_TPLHELPER2", $this->Gui->getSelectBoxOfEditableFiles($arrayBlockedSitesStandardPages, $arrayContent1, 2));
            
            $this->template = $this->Mandn->setBlock($this->template, "CONTENT_TPLHELPER3", $this->Gui->getSelectBoxOfEditableFiles(null, $arrayContent2, 1));
            $this->template = $this->Mandn->setBlock($this->template, "CONTENT_TPLHELPER4", $this->Gui->getSelectBoxOfEditableFiles(null, $arrayContent2, 2));
            
            $this->template = $this->Mandn->setBlock($this->template, "CONTENT_TPLHELPER5", $this->Gui->getSelectBoxOfEditableFiles(null, $arrayContent3, 1));
            $this->template = $this->Mandn->setBlock($this->template, "CONTENT_TPLHELPER6", $this->Gui->getSelectBoxOfEditableFiles(null, $arrayContent3, 2));
            
            $this->template = $this->Mandn->setBlock($this->template, "CONTENT_TPLHELPER7", $this->Gui->getSelectBoxOfEditableFiles(null, $arrayContent4, 1));
            $this->template = $this->Mandn->setBlock($this->template, "CONTENT_TPLHELPER8", $this->Gui->getSelectBoxOfEditableFiles(null, $arrayContent4, 2));
            
            // load dialog-editor
            $strDialogEditor = (file_exists("../cmsadmin/template/dialogeditor.tpl")) ? $this->Filehandle->readFilecontent("../cmsadmin/template/dialogeditor.tpl") : "";
            $this->template = $this->Mandn->setBlock($this->template, "DIALOGEDITOR", $strDialogEditor);
            $this->template = $this->Gui->writeStandardDialogEditor($this->template);
        } else {
            $this->readAndReplaceLoginSite();
        }
        $this->template = $this->Mandn->setBlock($this->template, "PASSWORDERROR", "");
        $this->Response->write($this->template);
    }

}