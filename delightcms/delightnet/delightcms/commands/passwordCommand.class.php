<?php

/*
 * building Changing PW and User - Site
 * 
 * @author    Gunnar von Spreckelsen <service@ihrwebentwickler.de>
 * @package   delightcms
 * @version   1.00
 * 
 */

namespace delightnet\delightcms\commands;

use delightnet\delightcms\CmsTemplateView;
use delightnet\delightcms\CmsTemplate;

class passwordCommand extends CmsTemplate implements CmsTemplateView {

    public function execute() {
        if ($this->getandSetSessionAndCmdEnvirement($this->Request->getParameter('cmd'), $this->Request->getParameter('user'), $this->Request->getParameter('password')) === true) {
            $this->buildCmsTemplate($this->Request);

            if ($this->Request->getParameter('action_pw') == "Speichern" && $this->Request->getParameter('pw_old') != "" && $this->Request->getParameter('pw_new') != "") {
                $isWhiteList = $this->Security->checkOfWhitelist($this->Request->getParameter('pw_new'));
                $isWhiteList = ($isWhiteList === true && $this->Request->getParameter('user_new') != "") ?
                $this->Security->checkOfWhitelist($this->Request->getParameter('user_new')) : false;

                if (
                        $isWhiteList === true
                        && $this->SessionAndSecurityData->sessionUserData["users"]["password"] == $this->Request->getParameter('pw_old')
                        && $this->Request->getParameter('pw_new') != ""
                        && $this->Request->getParameter('pw_old') != ""
                ) {
                    $user = ($this->Request->getParameter('user_new') == "") ? "" : $this->Security->undoTags($this->Request->getParameter('user_new'));
                    $pw = $this->Security->undoTags($this->Request->getParameter('pw_new'));
                    $filelink = "../cmsadmin/configuration/users.ini";

                    $arrayUser["users"] = array(
                        "user" => $user,
                        "password" => $pw
                    );

                    $this->Filehandle->writeSimpleIniFile($filelink, $arrayUser);
                    $this->Response->addHeader("location", "login.html");
                } else {
                    $errorPart = ($isWhiteList === false) ?
                            $this->Filehandle->readFilecontent("../cmsadmin/template/parts/pw_whitelist_error.tpl")
                                : $this->Filehandle->readFilecontent("../cmsadmin/template/parts/pw_compare_error.tpl");
                    $this->template = $this->Mandn->setBlock($this->template, "PASSWORDERROR", $errorPart);
                }
            } else {
                $this->template = $this->Mandn->setBlock($this->template, "PASSWORDERROR", "");
            }
        } else {
            $this->readAndReplaceLoginSite();
        }
 
        $this->Response->write($this->template);
    }

}