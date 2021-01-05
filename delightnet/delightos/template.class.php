<?php

/*
 * basic-template class for building classic html-sites and device-templates
 *
 * @author    Gunnar von Spreckelsen <service@ihrwebentwickler.de>
 * @package   delightos
 */

namespace delightnet\delightos;

use Exception;

abstract class Template {
    protected Request $objRequest;
    protected Response $objResponse;
    public string $strAlpha2;
    public ?array $objMainConfiguration;
    public array $arrSystemEnv;
    public string $cmd;
    public string $action;
    public string $template;
    public string $dynamicSite;
    public string $strActivThemeLink;
    public ?object $objTheme;
    public object $objLangs;
    public ?object $objDynamicFiles;
    public ?object $objMenuEntry;
    public array $arrDevices;
    public ?array $arrExtensions;

    public Filehandle $Filehandle;
    public MandN $MandN;
    public Security $Security;
    public Session $Session;

    public function __construct(Request $objRequest, Response $objResponse) {
        $this->Filehandle = new Filehandle();
        $this->MandN = new MandN($this->Filehandle, $objRequest);
        $this->Security = new Security();
        $this->Session = new Session();

        $this->objMainConfiguration = (file_exists("public/configuration/main.json")) ?
            json_decode(
                $this->Filehandle->readFilecontent("public/configuration/main.json"), true
            ) : false;

        $this->objLangs = (file_exists("public/configuration/lang.json")) ?
            json_decode($this->Filehandle->readFilecontent("public/configuration/lang.json")) : "";
        $this->objTheme = (file_exists("public/configuration/themes.json")) ?
            json_decode($this->Filehandle->readFilecontent("public/configuration/themes.json")) : null;
        $this->objDynamicFiles = (file_exists("public/configuration/staticfiles.json")) ?
            json_decode($this->Filehandle->readFilecontent("public/configuration/staticfiles.json")) : null;
        $this->arrSystemEnv["isMobileDevice"] = false;

        if (file_exists("public/configuration/devices.php")) {
            include_once("public/configuration/devices.php");
            $this->arrDevices = (!empty($devices)) ? $devices : false;
        }

        $this->arrExtensions =
            (file_exists("public/configuration/extensions.json")) ?
                json_decode($this->Filehandle->readFilecontent(
                    "public/configuration/extensions.json"), true
                ) : null;

        $this->objRequest = $objRequest;
        $this->objResponse = $objResponse;
    }

    /**
     * set default-language and new language by user-click
     * @return void
     */
    public function setLangEnv(): void {
        if (
            $this->action !== false && $this->action !== $this->Session->getSession('alpha2')
            && in_array($this->action, $this->objLangs->lang, true)
        ) {
            $this->Session->setSession('alpha2', $this->action);
        }

        if ($this->Session->getSession('alpha2') === "") {
            $this->strAlpha2 = $this->objMainConfiguration['main']["defaults"]["alpha2"];
            $this->Session->setSession('alpha2', $this->action);
        } else {
            $this->strAlpha2 = $this->Session->getSession('alpha2');
        }
    }

    /**
     * set current system-command/ filename
     * @return void
     * @throws Exception
     */
    public function setCmd(): void {
        if ($this->objRequest->getParameter('cmd') != "") {
            $cmd = $this->objRequest->getParameter('cmd');
            $cmd = $this->Security->undoMagicQuotes($cmd);
            $this->cmd = strip_tags($cmd);
        } elseif (isset($this->objMainConfiguration['main']["defaults"]["error"])) {
            $this->cmd = $this->objMainConfiguration['main']["defaults"]["error"];
        } else {
            throw new Exception('Error: missing regular CMD-command');
        }
    }

    /**
     * set current action-command
     * @return void
     */
    public function setAction(): void {
        if ($this->objRequest->getParameter('action') != "") {
            $this->action = $this->objRequest->getParameter('action');
            $this->action = $this->Security->undoMagicQuotes($this->action);
            $this->action = strip_tags($this->action);
        } else {
            $this->action = false;
        }
    }

    /**
     * load and set current dynamic-site html-data
     * @return void
     */
    public function setDynamicSite(): void {
        // if exists load special complete-lang-templatefile
        if (file_exists("public/template/lang/" . $this->strAlpha2 . "/" . $this->cmd . ".tpl")) {
            $this->dynamicSite = $this->Filehandle->readFilecontent("public/template/lang/"
                . $this->strAlpha2 . "/" . $this->cmd . ".tpl");
        } else {
            if (file_exists("public/template/" . $this->cmd . ".tpl")) {
                $this->dynamicSite =
                    $this->Filehandle->readFilecontent("public/template/" . $this->cmd . ".tpl");
            } elseif (
                isset($this->objMainConfiguration['main']["defaults"]["error"])
                && file_exists("public/template/"
                    . $this->objMainConfiguration['main']["defaults"]["error"] . ".tpl")
            ) {
                $this->dynamicSite = $this->Filehandle->readFilecontent("public/template/" .
                    $this->objMainConfiguration['main']["defaults"]["error"] . ".tpl");
                $this->cmd = $this->objMainConfiguration['main']["defaults"]["error"];
            } else {
                $this->cmd = "";
                $this->dynamicSite = "";
            }
        }

        $this->template = $this->MandN->setBlock($this->template, "CONTENT", $this->dynamicSite);
    }

    /**
     * replace translation-blocks in html-output-site
     * @return void
     */
    public function replaceLanguageTexts(): void {
        if (strpos($this->template, "{L:") !== false) {
            $arrayLangFiles = [];
            $defaultLang = $this->objLangs->lang[$this->objLangs->defaultLanguage];

            // 1.) public/template/lang/{lang}/{cmd}.json
            array_push($arrayLangFiles, (object)[
                'cmdKey' => $this->cmd,
                'fileLink' => file_exists(
                    "public/template/lang/" . $this->strAlpha2 . "/" . $this->cmd . ".json"
                ) ?
                    "public/template/lang/" . $this->strAlpha2 . "/" . $this->cmd . ".json" : null
            ]);

            if (
                isset($arrayLangFiles[count($arrayLangFiles) - 1])
                && !isset($arrayLangFiles[count($arrayLangFiles) - 1]->fileLink)
                && file_exists("public/template/lang/" . $defaultLang . "/" . $this->cmd . ".json")) {
                $arrayLangFiles[count($arrayLangFiles) - 1]->fileLink =
                    "public/template/lang/" . $defaultLang . "/" . $this->cmd . ".json";
            }

            // 2.) "public/template/lang/{lang}/template.json"
            array_push($arrayLangFiles, (object)[
                'cmdKey' => 'template',
                'fileLink' => file_exists("public/template/lang/" . $this->strAlpha2 . "/template.json") ?
                    "public/template/lang/" . $this->strAlpha2 . "/template.json" : null
            ]);
            if (
                isset($arrayLangFiles[count($arrayLangFiles) - 1])
                && !isset($arrayLangFiles[count($arrayLangFiles) - 1]->fileLink)
                && file_exists("public/template/lang/" . $this->strAlpha2 . "/template.json")) {
                $arrayLangFiles[count($arrayLangFiles) - 1]->fileLink =
                    "public/template/lang/" . $defaultLang . "/template.json";
            }

            // 3.) "public/extensions/{cmd}/template/lang/{lang}/{cmd}.json"
            array_push($arrayLangFiles, (object)[
                'cmdKey' => $this->cmd,
                'fileLink' => file_exists("public/extensions/" . $this->cmd . "/template/lang/" .
                    $this->strAlpha2 . "/" . $this->cmd . ".json") ?
                    "public/extensions/" . $this->cmd . "/template/lang/" . $this->strAlpha2 . "/" . $this->cmd .
                    ".json" : null
            ]);
            if (
                isset($arrayLangFiles[count($arrayLangFiles) - 1])
                && !isset($arrayLangFiles[count($arrayLangFiles) - 1]->fileLink)
                && file_exists("public/extensions/" . $this->cmd . "/template/lang/" .
                    $this->strAlpha2 . "/" . $this->cmd . ".json")) {
                $arrayLangFiles[count($arrayLangFiles) - 1]->fileLink =
                    "public/extensions/" . $defaultLang . "/template/lang/" .
                    $this->strAlpha2 . "/" . $defaultLang . ".json";
            }

            foreach ($arrayLangFiles as $key => $object) {
                if (isset($object->fileLink)) {
                    $decodedFileContent = json_decode($this->Filehandle->cleanJsonStr(
                        $this->Filehandle->readFilecontent($object->fileLink)));
                    if (isset($decodedFileContent->{$object->cmdKey})) {
                        foreach ($decodedFileContent->{$object->cmdKey} as $keyReplace => $valueReplace) {
                            $this->template = $this->MandN->setBlock(
                                $this->template,
                                "L:" . strtoupper($keyReplace),
                                $valueReplace
                            );
                        }
                    }
                }
            }
        }
    }

    /**
     * replace loop-blocks in template
     * @return void
     */
    public function replaceLoopBlocks(): void {
        $defaultLang = $this->objLangs->lang[$this->objLangs->defaultLanguage];
        $fileLink = file_exists("public/template/lang/" . $this->strAlpha2 . "/" . $this->cmd . ".json") ?
            "public/template/lang/" . $this->strAlpha2 . "/" . $this->cmd . ".json" : null;

        if (
            !isset($fileLink)
            && file_exists("public/template/lang/" . $defaultLang . "/" . $this->cmd . ".json")
        ) {
            $fileLink = "public/template/lang/" . $defaultLang . "/" . $this->cmd . ".json";
        }

        if (isset($fileLink)) {
            $decodedFileContent = json_decode(
                $this->Filehandle->cleanJsonStr($this->Filehandle->readFilecontent($fileLink))
            );
            if (isset($decodedFileContent->loop)) {
                $this->template = $this->MandN->replaceLoops($decodedFileContent, $this->template);
            }
        }
    }

    /**
     * replaces dynamic-file-integrations to current content-site
     * @return void
     */
    public function replaceDynamicFiles(): void {
        $arrayDynamicFilesObj = [];
        $arrayDynamicFilesObj = (isset($this->objDynamicFiles->dynamicFiles->permanent)) ?
            array_merge($arrayDynamicFilesObj, $this->objDynamicFiles->dynamicFiles->permanent) : $arrayDynamicFilesObj;
        $arrayDynamicFilesObj = ($this->arrSystemEnv["isDevice"] && isset($this->objDynamicFiles->dynamicFiles->device)) ?
            array_merge($arrayDynamicFilesObj, $this->objDynamicFiles->dynamicFiles->device) : $arrayDynamicFilesObj;

        foreach ($arrayDynamicFilesObj as $key => $arrDynamicFilesOfSite) {
            if (is_string($arrDynamicFilesOfSite) && strlen($arrDynamicFilesOfSite) > 0) {
                $this->template = $this->MandN->replaceDynamicLinks($this->template, $arrDynamicFilesOfSite);
            }
        }
    }

    /**
     * set current and activ theme-env
     * @return void
     */
    public function setThemeEnv(): void {
        $this->strActivThemeLink = "";

        if ($this->objTheme != null) {
            foreach ($this->objTheme->themes as $key => $objThemeData) {
                if ($this->objTheme->themes[$key]->isActiv === true) {
                    $this->strActivThemeLink = $this->objTheme->themes[$key]->name;
                    break;
                }
            }
        }

        $this->strActivThemeLink = ($this->strActivThemeLink == "") ?
            "public/template" : "public/themes/" . $this->strActivThemeLink . "/";
        $this->template = (file_exists($this->strActivThemeLink . "template.tpl")) ?
            $this->Filehandle->readFilecontent($this->strActivThemeLink . "template.tpl") : "";
        $this->template = (file_exists($this->strActivThemeLink . "template.tpl")) ?
            $this->Filehandle->readFilecontent($this->strActivThemeLink . "template.tpl") : "";
    }

    /**
     * replace html-lang-data in template
     * @return void
     */
    public function replaceLang(): void {
        $strHtmlLang = (file_exists("public/template/parts/lang.tpl")) ?
            $this->Filehandle->readFilecontent("public/template/parts/lang.tpl") : "";
        $strHtmlLangStyle = (file_exists("public/template/parts/langstyle.tpl")) ?
            $this->Filehandle->readFilecontent("public/template/parts/langstyle.tpl") : "";
        $strOutputHtmlLang = "";

        if ($this->objMenuEntry != null && isset($this->objLangs)) {
            if (is_array($this->objLangs->lang)) {
                foreach ($this->objLangs->lang as $key => $value) {
                    $strOutputHtmlLang .= $strHtmlLang;
                    $strActivStyle = ($value == $this->strAlpha2) ? $strHtmlLangStyle : "";
                    $strOutputHtmlLang = $this->MandN->setBlock(
                        $strOutputHtmlLang,
                        "LANGSTYLE",
                        $strActivStyle
                    );
                    $strOutputHtmlLang = $this->MandN->setBlock($strOutputHtmlLang, "LANG", $value);
                    $strOutputHtmlLang = $this->MandN->setBlock($strOutputHtmlLang, "CMD", $this->cmd);
                }
            }
        }

        $this->template = $this->MandN->setBlock($this->template, "LANGNAVIGATION", $strOutputHtmlLang);
    }

    /**
     * replace html-data of menus in template
     * @return void
     */
    public function replaceMenu(): void {
        if (isset($this->objMainConfiguration['main']["defaults"]["menustyle"]) &&
            file_exists("public/template/" . $this->objMainConfiguration['main']["defaults"]["menustyle"])
            && !empty($this->objMainConfiguration['main']["menu"])
        ) {
            $strMenuStyle = $this->Filehandle->readFilecontent("public/template/" .
                $this->objMainConfiguration['main']["defaults"]["menustyle"]);
            $arrayLoopInput = [];

            $this->objMenuEntry =
                (file_exists("public/template/lang/" . $this->strAlpha2 . "/menuentry.json")) ?
                    json_decode($this->Filehandle->readFilecontent("public/template/lang/" .
                        $this->strAlpha2 . "/menuentry.json")) : null;

            if ($this->objMenuEntry != null) {
                foreach ($this->objMainConfiguration['main']["menu"] as $menuKey => $arrMenu) {
                    foreach ($arrMenu as $key => $filename) {
                        $arrayLoopInput[$menuKey][$key]['FILENAME'] = $filename;
                        $arrayLoopInput[$menuKey][$key]['SITE'] = $this->objMenuEntry->menuentry->{$filename};

                        // mobile responsive menü, dynamic integration of various device-menus
                        if (strstr($this->template, "MENU" . $menuKey . "-DEVICE") == true) {
                            $arrayLoopInputDevice[$menuKey][$key]['FILENAME'] = $filename;
                            $arrayLoopInputDevice[$menuKey][$key]['SITE'] = $this->objMenuEntry->menuentry->{$filename};
                        }
                    }
                }
            }

            if (count($arrayLoopInput) > 0) {
                foreach ($arrayLoopInput as $menuKey => $arrayMenu) {
                    $this->template = $this->MandN->replaceLoopContent($this->template, 'MENU' .
                        $menuKey, $arrayMenu, "MENU_ACTIV", $strMenuStyle, $this->cmd, "FILENAME");
                }

                if (strstr($this->template, "-DEVICE}")) {
                    foreach ($arrayLoopInput as $menuKey => $arrayMenu) {
                        $this->template = $this->MandN->replaceLoopContent($this->template, 'MENU' .
                            $menuKey . "-DEVICE", $arrayMenu, "MENU_ACTIV_DEVICE", $strMenuStyle, $this->cmd, "FILENAME");
                    }
                }
            }
        }
    }

    /**
     * dynamic method for replacing different html-data to template (f.e. sitetitle and breadcrumb)
     * @param string $strObjName
     * @return void
     */
    public function replaceHtmlData(string $strObjName): void {
        $objHtmlData = (file_exists(
            "public/template/lang/" . $this->strAlpha2 . "/" . $strObjName . ".json")
        ) ? json_decode($this->Filehandle->readFilecontent(
            "public/template/lang/" . $this->strAlpha2 . "/" . $strObjName . ".json")
        ) : null;

        if (isset ($objHtmlData->{$strObjName}->{$this->cmd})) {
            $htmlValue = $objHtmlData->{$strObjName}->{$this->cmd};
        } else {
            $htmlValue = "";
        }

        $this->template = $this->MandN->setBlock($this->template, strtoupper($strObjName), $htmlValue);
    }

    /**
     * replace js-frontend-data of device-information to html-data
     * @return void
     */
    public function setGlobalEnv(): void {
        if ($this->arrDevices != null) {
            $ua = $this->objRequest->getHttpUserAgent();
            $this->arrSystemEnv["isDevice"] = "false";

            // Operating system
            foreach ($this->arrDevices['os'] as $k => $v) {
                if (stripos($ua, $k) !== false) {
                    $this->arrSystemEnv["os"] = $v['os'];
                    $this->arrSystemEnv["isDevice"] = (string)$v['isDevice'];
                    break;
                }
            }
        }
    }

    abstract public function buildTemplate();
}