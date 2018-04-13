<?php

/*
 * CMS-admin-site functionality
 * main-functions  for playout CMS-Backend
 * 
 * @author    Gunnar von Spreckelsen <service@ihrwebentwickler.de>
 * @package   delightcms
 * @version   4.1
 * 
 */

namespace delightnet\delightcms;

use delightnet\delightos\Request;
use delightnet\delightos\Response;

abstract class CmsTemplate implements CmsTemplateView {

    public $Filehandle;
    public $Mandn;
    public $Security;
    public $Session;
    public $Gui;
    public $template;
    public $arrayDynamicFiles;
    public $arrayExtensions;
    public $strMenuEntry;
    protected $Request;
    protected $Response;
    public $cmd;
    public $extSystemName;
    public $arrayLanguagePackage;
    public $DynamicSite;

    public function __construct(Request $request, Response $response) {
        $this->Filehandle = new \delightnet\delightos\Filehandle();
        $this->Mandn = new \delightnet\delightos\MandN($this->Filehandle, $request);
        $this->Security = new \delightnet\delightos\Security();
        $this->Session = new \delightnet\delightcms\Session();
        $this->Gui = new \delightnet\delightcms\Gui($this->Filehandle, $this->Mandn, $this->Security);

        $this->template = (file_exists("../cmsadmin/template/template.tpl")) ? $this->Filehandle->readFilecontent("../cmsadmin/template/template.tpl") : "";
        $this->arrayDynamicFiles = (file_exists("../cmsadmin/configuration/dynamicfiles.ini")) ? $this->Filehandle->transformIniFileToMutipleArray("../cmsadmin/configuration/dynamicfiles.ini") : null;
        $this->arrayExtensions = (file_exists("../delightnet/extensions/extensions.ini")) ? $this->Filehandle->transformIniFileToMutipleArray("../delightnet/extensions/extensions.ini") : null;
        $this->strMenuEntry = (file_exists("../cmsadmin/template/parts/menuEntry.tpl")) ? $this->Filehandle->readFilecontent("../cmsadmin/template/parts/menuEntry.tpl") : null;
        $this->arrayLang = (file_exists("../cmsadmin/configuration/lang/lang.ini")) ? $this->Filehandle->transformIniFileToMutipleArray("../cmsadmin/configuration/lang/lang.ini") : null;
        $this->Request = $request;
        $this->Response = $response;

        $this->extSystemName = ($this->Request->getParameter("extSystemName")) ? $this->Request->getParameter("extSystemName") : "";
        $this->extSystemName = $this->Security->undoTags($this->extSystemName);
    }

    /**
     * set correct CMS-command-file to template-property, including the dynamic-site
     *  
     * @param string $cmd
     */
    public function setCmsCommandFile($cmd) {
        if ($cmd == "login" || $cmd == "logout" || $cmd == "error") {
            $this->cmd = (file_exists("../cmsadmin/template/login.tpl")) ? $cmd : "login";
        }
        else {
            $this->cmd = (file_exists("../cmsadmin/template/" . $cmd . ".tpl")) ? $cmd : "login";
        }
    }

    /**
     * set current language-package to class-property
     */
    public function setLanguageTranslation() {
        $intCurrentLangId = 1;
        $strCurrentIsoCountryShortcut = "d";

        if (isset($this->arrayLang['defaults']['currentLanguage'])) {
            $intCurrentLangId = $this->arrayLang['defaults']['currentLanguage'];
        }

        if (isset($this->arrayLang['lang'][$intCurrentLangId])) {
            $strCurrentIsoCountryShortcut = $this->arrayLang['lang'][$intCurrentLangId];
        }

        $this->arrayLanguagePackage = (file_exists("../cmsadmin/configuration/lang/" . $strCurrentIsoCountryShortcut . ".ini")) ? $this->Filehandle->transformIniFileToMutipleArray("../cmsadmin/configuration/lang/" . $strCurrentIsoCountryShortcut . ".ini") : null;
    }

    /**
     * replace language-package-data to template
     */
    public function ReplaceLanguageTranslation() {
        // replace dynamic-site
        if (isset($this->arrayLanguagePackage[$this->cmd])) {
            foreach ($this->arrayLanguagePackage[$this->cmd] as $markername => $langtext) {
                $this->template = $this->Mandn->setBlock($this->template, "L:" . strtoupper($markername), $langtext);
            }
        }
    }

    /**
     * get and set of regular session and Data-Check of cms-user
     *  
     * @param string $cmd
     * @param string $user
     * @param string $password
     * @return \stdClass
     */
    public function getandSetSessionAndCmdEnvirement($cmd, $user, $password) {
        $cmd = $this->Session->setSessionEnvirement($cmd, $this->Security->undoTags($user), $this->Security->undoTags($password));
        $isWhite = $this->Security->checkOfWhitelist($user);
        $isWhite = ($isWhite === false) ? $isWhite : $this->Security->checkOfWhitelist($password);

        if ($isWhite === false) {
            $cmd == "error";
        }

        $this->setCmsCommandFile($cmd);
        $this->setLanguageTranslation();

        if ($this->cmd == "error" || $this->cmd == "login" || $this->cmd == "logout" || $this->cmd == "sorry") {
            return false;
        }

        return true;
    }

    /**
     * read dynamic-File and save html-filecontent in class-property DynamicSite
     */
    public function readDynamicCmsContentFile() {
        $this->DynamicSite = (file_exists("../cmsadmin/template/" . $this->cmd . ".tpl")) ? $this->Filehandle->readFilecontent("../cmsadmin/template/" . $this->cmd . ".tpl") : "";
    }

    /**
     * replace dynamic-content to html-template
     */
    public function replaceDynamicCmsContent() {
        $this->template = $this->Mandn->setBlock($this->template, "CONTENT", $this->DynamicSite);
    }

    /**
     * replace current breadcrumb-text to dom of content-site
     */
    public function replaceBreadCrumbText() {
        if (isset($this->arrayLanguagePackage["Breadcrumb"][$this->cmd])) {
            $this->template = $this->Mandn->setBlock($this->template, "BREADCRUMB", $this->arrayLanguagePackage["Breadcrumb"][$this->cmd]);
        } else {
            $this->template = $this->Mandn->setBlock($this->template, "BREADCRUMB", "");
        }

        if ($this->extSystemName != "") {
            $this->template = $this->Mandn->setBlock($this->template, "EXTNAME", $this->extSystemName);
        }
    }

    
    /**
     * loading-action to login/logout-site if user has no login-status
     */
    public function readAndReplaceLoginSite() {
        if ($this->cmd == "login" || $this->cmd == "logout" || $this->cmd == "error") {
            $this->template = $this->Filehandle->readFilecontent("../cmsadmin/template/login.tpl");   
        }
        else {
            $this->template = $this->Filehandle->readFilecontent("../cmsadmin/template/" . $this->cmd . ".tpl"); 
        }

        if ($this->template != "") {
            if ($this->cmd == "sorry" && isset($this->arrayLanguagePackage['sorry']['sorrymessage'])
                    && isset($this->arrayLanguagePackage['sorry']['unlockmessage'])) {
                $this->template = $this->Mandn->setBlock($this->template, "SORRYMESSAGE", $this->arrayLanguagePackage['sorry']['sorrymessage']);
                $this->template = $this->Mandn->setBlock($this->template, "UNLOCKMESSAGE", $this->arrayLanguagePackage['sorry']['unlockmessage']);

                $intLogTime = (file_exists("../cmsadmin/configuration/loginstatus.ini")) ? $this->Filehandle->readFilecontent("../cmsadmin/configuration/loginstatus.ini") : "";
                $intLogTime = $intLogTime + 60 * 20;
                $this->template = $this->Mandn->setBlock($this->template, "UNLOCK_LOGGINTIME", date("d.m.Y H:i", $intLogTime));
            }
            
            if ($this->cmd != "sorry" && isset($this->arrayLanguagePackage['logmessage'][$this->cmd])) {
                $this->template = $this->Mandn->setBlock($this->template, "LOGMESSAGE", $this->arrayLanguagePackage['logmessage'][$this->cmd]);
            }
        }
    }
 

    /**
     * replaces dynamic-file-integations to current content-site
     */
    public function replaceDynamicCmsFileIntegration() {
        if (isset($this->arrayDynamicFiles["dynamicfiles"])) {
            $this->template = $this->Mandn->replaceDynamicFilesIntegration($this->template, false, $this->arrayDynamicFiles["dynamicfiles"], $this->Request->getdocumentRoot());
        }
    }

    /**
     * replace current click-status to save-button
     */
    public function replaceSaveConfirmation($isSaved) {
        if ($isSaved === true && isset($this->arrayLanguagePackage["pagemessages"]["classSaveOk"]) && isset($this->arrayLanguagePackage["pagemessages"]["textSaveOk"])) {
            $strClassSave = $this->arrayLanguagePackage["pagemessages"]["classSaveOk"];
            $strTextSave = $this->arrayLanguagePackage["pagemessages"]["textSaveOk"];
        } else {
            $strClassSave = "";
            $strTextSave = "";
        }

        $strSavebuttonPagehelper = (file_exists("../cmsadmin/template/parts/savebutton.tpl")) ? $this->Filehandle->readFilecontent("../cmsadmin/template/parts/savebutton.tpl") : "";

        $this->template = $this->Mandn->setBlock($this->template, "SAVEBUTTON_PAGEHELPER", $strSavebuttonPagehelper);
        $this->template = $this->Mandn->setBlock($this->template, "CLASS_SAVE_OK", $strClassSave);
        $this->template = $this->Mandn->setBlock($this->template, "TEXT_SAVE_OK", $strTextSave);
    }

    /**
     * dynamic replace of menu-help-entries to template-menu-dom if extension-help-file exists 
     */
    public function replaceExtensionHelpMenuEntries() {
        if ($this->strMenuEntry != null) {
            $strExtensionHelpMenuEntries = '';
            if (sizeof($this->arrayExtensions) > 0)
                foreach ($this->arrayExtensions as $class => $arrayExt) {
                    if ($arrayExt['active'] == 1 && file_exists("../delightnet/extensions/" . $class . "/cmsadmin/help.tpl")) {
                        $strExtensionHelpMenuEntries .= $this->strMenuEntry;
                        $strExtensionHelpMenuEntries = $this->Mandn->setBlock($strExtensionHelpMenuEntries, "LINK_MENUENTRY", 'index.php?extSystemName=' . $class . '&cmd=exthelp');
                        $strExtensionHelpMenuEntries = $this->Mandn->setBlock($strExtensionHelpMenuEntries, "TITLE_MENUENTRY", 'Hilfe zur Erweiterung ' . $class);
                        $strExtensionHelpMenuEntries = $this->Mandn->setBlock($strExtensionHelpMenuEntries, "NAME_MENUENTRY", $arrayExt['menuname']);
                        $strExtensionHelpMenuEntries = $strExtensionHelpMenuEntries . "\n";
                    }
                }

            $this->template = $this->Mandn->setBlock($this->template, "MENU_EXTHELP", $strExtensionHelpMenuEntries);
        } else {
            $this->template = $this->Mandn->setBlock($this->template, "MENU_EXTHELP", "");
        }
    }

    /**
     * replace configuration-entries to menu in template-file-dom
     */
    public function replaceExtensionConfMenuEntries() {
        if ($this->strMenuEntry != null) {
            $strExtensionConfMenuEntries = '';
            if (sizeof($this->arrayExtensions) > 0) {
                foreach ($this->arrayExtensions as $class => $arrayExt) {
                    if ($arrayExt['active'] == 1 && file_exists("../delightnet/extensions/" . $class . "/cmsadmin/" . $class . ".tpl") && $class != "Contact") {
                        $strExtensionConfMenuEntries .= $this->strMenuEntry;
                        $strExtensionConfMenuEntries = $this->Mandn->setBlock($strExtensionConfMenuEntries, "LINK_MENUENTRY", 'index.php?extSystemName=' . $class . '&cmd=extconf');
                        $strExtensionConfMenuEntries = $this->Mandn->setBlock($strExtensionConfMenuEntries, "TITLE_MENUENTRY", 'Konfiguration der Erweiterung ' . $class);
                        $strExtensionConfMenuEntries = $this->Mandn->setBlock($strExtensionConfMenuEntries, "NAME_MENUENTRY", $arrayExt['menuname']);
                        $strExtensionConfMenuEntries = $strExtensionConfMenuEntries . "\n";
                    }
                }
            }

            $this->template = $this->Mandn->setBlock($this->template, "MENU_EXTCONF", $strExtensionConfMenuEntries);
        } else {
            $this->template = $this->Mandn->setBlock($this->template, "MENU_EXTCONF", "");
        }
    }

    /**
     * replace language-translation-output to current content-site-markers
     */
    public function replaceLanguage() {
        if (isset($this->arrayLang['defaults']['currentLanguage'])) {
            $strCurrentIsoCountryShortcut = "d";
            $intCurrentLangId = $this->arrayLang['defaults']['currentLanguage'];

            if (isset($this->arrayLang['lang'][$intCurrentLangId])) {
                $strCurrentIsoCountryShortcut = $this->arrayLang['lang'][$intCurrentLangId];
            }

            foreach ($this->arrayLang['lang'] as $langKey => $strNewIsoCountryShortcut) {
                $strReplaceCountryMarker = ($strNewIsoCountryShortcut == $strCurrentIsoCountryShortcut) ? "choisedLang" : "";
                $this->template = $this->Mandn->setBlock($this->template, "CHOISED_LANG_" . strtoupper($strNewIsoCountryShortcut), $strReplaceCountryMarker);
            }
        }
    }

    /**
     * build whole dom-output of current content-site
     */
    public function buildCmsTemplate() {
        $this->readDynamicCmsContentFile();
        $this->replaceDynamicCmsContent();
        $this->ReplaceLanguageTranslation();
        $this->replaceDynamicCmsFileIntegration();
        $this->replaceBreadCrumbText();
        $this->replaceExtensionHelpMenuEntries();
        $this->replaceExtensionConfMenuEntries();
        $this->replaceLanguage();

        $this->template = $this->Mandn->setBlock($this->template, "DYNAMICFILEINTEGRATION", "");
    }

    /**
     * defination of abstract execute-function of command-classes 
     */
    abstract public function execute();
}