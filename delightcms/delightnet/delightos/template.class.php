<?php

/*
 * basic-template class for building classic html-sites and device-templates
 *
 * @author    Gunnar von Spreckelsen <service@ihrwebentwickler.de>
 * @package   delightos
 */

namespace delightnet\delightos;

abstract class Template
{
    protected $objRequest;
    protected $objResponse;
    public $strDirEnv;

    public $boolIsDeviceOutput;
    public $strAlpha2;
    public $arrMainConfiguration;
    public $arrSystemEnv;
    public $strDeviceOutputType;
    public $cmd;
    public $action;
    public $template;
    public $dynamicSite;
    public $strActivThemeLink;
    public $objTheme;
    public $arrLangs;
    public $objDynamicFiles;
    public $objMenuEntry;
    public $arrDevices;
    public $arrExtensions;

    public $Filehandle;
    public $MandN;
    public $Security;
    public $Session;

    public function __construct(Request $objRequest, Response $objResponse, $strDirEnv) {
        $this->Filehandle = new Filehandle();
        $this->MandN = new MandN($this->Filehandle, $objRequest);
        $this->Security = new Security();
        $this->Session = new Session();

        $this->arrMainConfiguration = (file_exists($strDirEnv . "configuration/main.json")) ?
            json_decode($this->Filehandle->readFilecontent($strDirEnv . "configuration/main.json"), true) : null;

        $this->arrLangs = (file_exists($strDirEnv . "configuration/lang.json")) ? json_decode($this->Filehandle->readFilecontent($strDirEnv . "configuration/lang.json")) : "";
        $this->objTheme = (file_exists($strDirEnv . "configuration/themes.json")) ?
            json_decode($this->Filehandle->readFilecontent($strDirEnv . "configuration/themes.json")) : null;
        $this->objDynamicFiles = (file_exists($strDirEnv . "configuration/dynamicfiles.json")) ? json_decode($this->Filehandle->readFilecontent($strDirEnv . "configuration/dynamicfiles.json")) : null;

        $this->arrSystemEnv["os"] = null;
        $this->arrSystemEnv["browser"] = null;
        $this->arrSystemEnv["version"] = null;
        $this->arrSystemEnv["isDevice"] = false;

        $this->arrDevices = null;
        if (file_exists($objRequest->getDocumentRoot() . "/public/configuration/devices.php")) {
            include_once($objRequest->getDocumentRoot() . "/public/configuration/devices.php");
            $this->arrDevices = (!empty($devices)) ? $devices : null;
        }

        $this->arrExtensions = (file_exists($objRequest->getDocumentRoot() . "/public/configuration/extensions.json")) ?
            json_decode($this->Filehandle->readFilecontent($objRequest->getDocumentRoot() . "/public/configuration/extensions.json"), true) : null;

        $this->objRequest = $objRequest;
        $this->objResponse = $objResponse;
        $this->strDirEnv = $strDirEnv;
    }

    /**
     * set default-language and new language by user-click
     */
    public function setLangEnv() {
        if ($this->action !== false && $this->action !== $this->Session->getSession('alpha2') && in_array($this->action, $this->arrLangs->lang, true)) {
            $this->Session->setSession('alpha2', $this->action);
        } else {
            // exception: lang not set
        }

        if ($this->Session->getSession('alpha2') === false) {
            $this->strAlpha2 = $this->arrMainConfiguration['main']["defaults"]["alpha2"];
            $this->Session->setSession('alpha2', $this->action);
        } else {
            $this->strAlpha2 = $this->Session->getSession('alpha2');
        }

        $this->objMenuEntry = (file_exists($this->strDirEnv . "template/lang/" . $this->strAlpha2 . "/menuentry.json")) ?
            json_decode($this->Filehandle->readFilecontent($this->strDirEnv . "template/lang/" . $this->strAlpha2 . "/menuentry.json")) : null;
    }

    /**
     * set current system-command/ filename
     */
    public function setCmd() {
        if ($this->objRequest->getParameter('cmd') != "") {
            $cmd = $this->objRequest->getParameter('cmd');
            $cmd = $this->Security->undoMagicQuotes($cmd);
            $this->cmd = $this->Security->undoTags($cmd);
        } elseif (isset($this->arrMainConfiguration['main']["defaults"]["error"])) {
            $this->cmd = $this->arrMainConfiguration['main']["defaults"]["error"];
        } else {
            throw new \Exception('Error: missing regular CMD-command');
        }
    }

    /**
     * set current action-command
     */
    public function setAction() {
        if ($this->objRequest->getParameter('action') != "") {
            $this->action = $this->objRequest->getParameter('action');
            $this->action = $this->Security->undoMagicQuotes($this->action);
            $this->action = $this->Security->undoTags($this->action);
        } else {
            $this->action = false;
        }
    }

    /**
     * load and set current dynamic-site html-data
     */
    public function setDynamicSite() {
        // if exists load special complete-lang-templatefile
        if (file_exists($this->strDirEnv . "template/lang/" . $this->strAlpha2 . "/" . $this->cmd . ".tpl")) {
            $this->dynamicSite = $this->Filehandle->readFilecontent($this->strDirEnv . "/template/lang/" . $this->strAlpha2 . "/" . $this->cmd . ".tpl");
        } else {
            if (file_exists($this->strDirEnv . "template/" . $this->cmd . ".tpl")) {
                $this->dynamicSite = $this->Filehandle->readFilecontent($this->strDirEnv . "/template/" . $this->cmd . ".tpl");
            } elseif (isset($this->arrMainConfiguration['main']["defaults"]["error"]) && file_exists($this->strDirEnv . "template/" . $this->arrMainConfiguration['main']["defaults"]["error"] . ".tpl")) {
                $this->dynamicSite = $this->Filehandle->readFilecontent($this->strDirEnv . "template/" . $this->arrMainConfiguration['main']["defaults"]["error"] . ".tpl");
                $this->cmd = $this->arrMainConfiguration['main']["defaults"]["error"];
            } else {
                $this->cmd = "";
                $this->dynamicSite = "";
            }
        }

        $this->template = $this->MandN->setBlock($this->template, "CONTENT", $this->dynamicSite);
    }

    /**
     * replace translation-blocks in html-output-site
     */
    public function replaceLanguageTexts() {
        $arrayLangFilesMain = array(
            "{$this->cmd}" => $this->strDirEnv . "template/lang/" . $this->strAlpha2 . "/" . $this->cmd . ".json",
            "template" => $this->strDirEnv . "template/lang/" . $this->strAlpha2 . "/template.json",
        );

        if (strpos($this->template, "{L:") !== false) {
            foreach ($arrayLangFilesMain as $cmdkey => $langfile) {
                if (file_exists($langfile)) {
                    $strLangMarkerMain = $this->Filehandle->readFilecontent($langfile);
                    $objLangMarkerMain = json_decode($this->Filehandle->cleanJsonStr($strLangMarkerMain));
                    if (is_object($objLangMarkerMain->{$cmdkey})) {
                        foreach ($objLangMarkerMain->{$cmdkey} as $key => $value)
                            $this->template = $this->MandN->setBlock($this->template, "L:" . strtoupper($key), $value);
                    }
                }
            }

            if (file_exists($this->strDirEnv . "extensions/" . $this->cmd . "/template/lang/" . $this->strAlpha2 . "/" . $this->cmd . ".json")) {
                $objLangMarkerExtension = json_decode($this->Filehandle->readFilecontent($this->strDirEnv . "extensions/" . $this->cmd . "/template/lang/" . $this->strAlpha2 . "/" . $this->cmd . ".json"));
                if (is_object($objLangMarkerExtension->{$this->cmd})) {
                    foreach ($objLangMarkerExtension->{$this->cmd} as $key => $value)
                        $this->template = $this->MandN->setBlock($this->template, "L:" . strtoupper($key), $value);
                }
            }
        }
    }

    /**
     * replaces dynamic-file-integrations to current content-site
     */
    public function replaceDynamicFiles() {
        $currentFile = $this->cmd;
        $arrayDynamicFilesObj[] = (isset($this->objDynamicFiles->dynamicFiles->permanent->template)) ? $this->objDynamicFiles->dynamicFiles->permanent->template : null;
        $arrayDynamicFilesObj[] = (isset($this->objDynamicFiles->dynamicFiles->permanent->$currentFile)) ? $this->objDynamicFiles->dynamicFiles->permanent->$currentFile : null;

        $arrayDynamicFilesObj[] = (isset($this->objDynamicFiles->dynamicFiles->{$this->strDeviceOutputType}->template)) ? $this->objDynamicFiles->dynamicFiles->{$this->strDeviceOutputType}->template : null;
        $arrayDynamicFilesObj[] = (isset($this->objDynamicFiles->dynamicFiles->{$this->strDeviceOutputType}->$currentFile)) ? $this->objDynamicFiles->dynamicFiles->{$this->strDeviceOutputType}->$currentFile : null;

        foreach ($arrayDynamicFilesObj as $key => $arrDynamicFilesOfSite) {
            if (!empty($arrDynamicFilesOfSite)) {
                $this->template = $this->MandN->replaceDynamicLinks($this->template, $arrDynamicFilesOfSite);
            }
        }
    }

    /**
     * set current and activ theme-env
     */
    public function setThemeEnv() {
        $this->strActivThemeLink = "";

        if ($this->objTheme != null) {
            foreach ($this->objTheme->themes as $key => $objThemeData) {
                if ($this->objTheme->themes[$key]->isActiv === true) {
                    $this->strActivThemeLink = $this->objTheme->themes[$key]->name;
                    break;
                }
            }
        }

        $this->strActivThemeLink = ($this->strActivThemeLink == "") ? $this->strDirEnv . "/template" : $this->strDirEnv . "themes/" . $this->strActivThemeLink . "/";
        $this->template = (file_exists($this->strActivThemeLink . "template.tpl")) ? $this->Filehandle->readFilecontent($this->strActivThemeLink . "template.tpl") : "";
    }

    /**
     * replace html-lang-data in template
     */
    public function replaceLang() {
        $strHtmlLang = (file_exists($this->strDirEnv . "template/parts/lang.tpl")) ? $this->Filehandle->readFilecontent($this->strDirEnv . "template/parts/lang.tpl") : "";
        $strHtmlLangStyle = (file_exists($this->strDirEnv . "template/parts/langstyle.tpl")) ? $this->Filehandle->readFilecontent($this->strDirEnv . "template/parts/langstyle.tpl") : "";
        $strOutputHtmlLang = "";

        if ($this->objMenuEntry != null && isset($this->arrLangs)) {
            if (is_array($this->arrLangs->lang)) {
                foreach ($this->arrLangs->lang as $key => $value) {
                    $strOutputHtmlLang .= $strHtmlLang;
                    $strActivStyle = ($value == $this->strAlpha2) ? $strHtmlLangStyle : "";
                    $strOutputHtmlLang = $this->MandN->setBlock($strOutputHtmlLang, "LANGSTYLE", $strActivStyle);
                    $strOutputHtmlLang = $this->MandN->setBlock($strOutputHtmlLang, "LANG", $value);
                    $strOutputHtmlLang = $this->MandN->setBlock($strOutputHtmlLang, "CMD", $this->cmd);
                }
            }
        }

        $this->template = $this->MandN->setBlock($this->template, "LANGNAVIGATION", $strOutputHtmlLang);
    }

    /**
     * replace html-data by activ multi-design
     */
    public function replaceMultidesign() {
        $strMultiDesign = "";

        if (isset($this->arrMainConfiguration['main']["multidesign"]["activ"])
            && $this->arrMainConfiguration['main']["multidesign"]["activ"] === true
            && !empty($this->arrMainConfiguration['main']["multidesign"]["modus"])
            && $this->boolIsDeviceOutput === false
            && file_exists($this->strDirEnv . "/template/parts/multidesign.tpl")
            && file_exists($this->strDirEnv . "/template/parts/screendimensions.tpl")
        ) {
            $strMultiDesign = $this->Filehandle->readFilecontent($this->strDirEnv . "/template/parts/multidesign.tpl");
            $strScreenDimensions = $this->Filehandle->readFilecontent($this->strDirEnv . "/template/parts/screendimensions.tpl");
            $strOutputScreenDimensions = "";

            foreach ($this->arrMainConfiguration['main']["multidesign"]["modus"] as $key => $value) {
                $strDimension = $strScreenDimensions;
                $arrResolutionSplit = explode('x', $key);

                if ($value == 1) {
                    $strOutputScreenDimensions .= $this->MandN->setBlock($strDimension, "SCREENWIDTH", $arrResolutionSplit[0]);
                    $strOutputScreenDimensions = $this->MandN->setBlock($strDimension, "SCREENWHEIGHT", $arrResolutionSplit[1]);
                }
            }

            $strMultiDesign = $this->MandN->setBlock($strMultiDesign, "SCREENDIMENSIONS", $strOutputScreenDimensions);
        }

        $this->template = $this->MandN->setBlock($this->template, "MULTIDESIGN", $strMultiDesign);
    }

    /**
     * replace html-data of menus in template
     */
    public function replaceMenu() {
        if (!empty($this->arrMainConfiguration['main']["menu"])) {
            $arrayLoop = array();

            if (isset($this->arrMainConfiguration['main']["defaults"]["menustyle"]) &&
                file_exists($this->strDirEnv . "template/" . $this->arrMainConfiguration['main']["defaults"]["menustyle"])) {
                $strMenuStyle = $this->Filehandle->readFilecontent($this->strDirEnv . "template/" . $this->arrMainConfiguration['main']["defaults"]["menustyle"]);
            } else {
                $strMenuStyle = "";
            }

            foreach ($this->arrMainConfiguration['main']["menu"] as $key => $menu) {
                foreach ($menu as $value) {
                    $object = new \stdClass();

                    $object->configuration = new \stdClass();
                    $object->configuration->identifier = "MENU" . $key;

                    $object->data = new \stdClass();
                    $object->data->filename = $value;
                    $object->data->site = $this->objMenuEntry->menuentry->{$value};
                    $object->data->replaceNames = [];
                    $object->data->replaceNames[] = "filename";
                    $object->data->replaceNames[] = "site";

                    $arrayLoop[] = $object;
                }
            }

            $this->template = $this->MandN->replaceLoopContent($this->template, $arrayLoop, $strMenuStyle, $this->cmd);

            // mobile responsive menü, dynamic integration of various device-menus
            if (strstr($this->template, "MENU" . $key . "-DEVICE") == true) {
                $this->template = $this->MandN->replaceLoopContent($this->template, "MENU" . $key . "-DEVICE", $arrayLoop, $strMenuStyle, $this->cmd);
            }
        }
    }

    /**
     * dynamic method for replacing different html-data to template (f.e. sitetitle and breadcrumb)
     */
    public function replaceHtmlData($strObjName) {
        $objHtmlData = (file_exists($this->strDirEnv . "/template/lang/" . $this->strAlpha2 . "/" . $strObjName . ".json")) ?
            json_decode($this->Filehandle->readFilecontent($this->strDirEnv . "/template/lang/" . $this->strAlpha2 . "/" . $strObjName . ".json")) : null;

        if (isset ($objHtmlData->{$strObjName}->{$this->cmd})) {
            $htmlValue = $objHtmlData->{$strObjName}->{$this->cmd};
        } else {
            $htmlValue = "";
        }

        $this->template = $this->MandN->setBlock($this->template, strtoupper($strObjName), $htmlValue);
    }

    /**
     * replace js-frontend-data of device-information to html-data (isDevice, browser etc)
     */
    public function setGlobalEnv() {
        if ($this->arrDevices != null) {
            $ua = $this->objRequest->getHttpUserAgent();
            $shorty = '';
            $version = null;
            $this->arrSystemEnv["isDevice"] = false;

            // Operating system
            foreach ($this->arrDevices['os'] as $k => $v) {
                if (stripos($ua, $k) !== false) {
                    $this->arrSystemEnv["os"] = $v['os'];
                    $this->arrSystemEnv["isDevice"] = (boolean)$v['isDevice'];
                    break;
                }
            }

            // Browser and version
            foreach ($this->arrDevices['browser'] as $k => $v) {
                if (stripos($ua, $k) !== false) {
                    $this->arrSystemEnv["browser"] = $v['browser'];
                    $shorty = $v['shorty'];
                    $version = preg_replace($v['version'], '$1', $ua);
                    break;
                }
            }

            $versions = explode('.', $version);
            if (isset($versions[0])) {
                $this->arrSystemEnv["version"] = $versions[0];
            }
        }

        // switch to devices-output (hardcoded)
        // $this->arrSystemEnv["isDevice"] = true;

        $this->strDeviceOutputType = ($this->arrSystemEnv["isDevice"] === true) ? "device" : "standard";
    }

    abstract public function buildTemplate();
}