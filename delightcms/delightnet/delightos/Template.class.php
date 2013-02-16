<?php

/*
 * basic template class for building classic html-sites and device-template
 * 
 * @author    Gunnar von Spreckelsen <service@ihrwebentwickler.de>
 * @package   delightos
 * @version   3.11
 * 
 */

namespace delightnet\delightos;

use delightnet\delightos\Request;
use delightnet\delightos\Response;
use delightnet\delightos\Registry;

class Template {

    public $template;
    public $DynamicSite;
    public $arrayDevices;
    public $arrayCurrentSystemEnv;
    public $Mandn;
    public $Security;
    public $Filehandle;
    public $arrayMainConfiguration;
    public $arrayExtensions;
    public $arraySystemSettings;
    public $isDeviceOutput;
    public $strGoogleSiteVerification;
    public $strGoogleAnalytics;
    public $strHtmlLang;
    public $arrayLangText;
    protected $documentRoot;
    protected $Request;
    protected $Response;

    public function __construct(Request $request, Response $response) {
        $this->Mandn = new \delightnet\delightos\MandN();
        $this->Security = new \delightnet\delightos\Security();
        $this->Filehandle = new \delightnet\delightos\Filehandle();

        $this->arrayMainConfiguration = (file_exists("public/configuration/main.ini")) ? $this->Filehandle->transformIniFileToMutipleArray("public/configuration/main.ini") : null;
        $this->arrayExtensions = (file_exists("delightnet/extensions/extensions.ini")) ? $this->Filehandle->transformIniFileToMutipleArray("delightnet/extensions/extensions.ini") : null;
        $this->arraySystemSettings = (file_exists("cmsadmin/configuration/systemsettings.ini")) ? $this->Filehandle->transformIniFileToMutipleArray("cmsadmin/configuration/systemsettings.ini") : null;

        $this->template = (file_exists("public/template/template.tpl")) ? $this->Filehandle->readFilecontent("public/template/template.tpl") : null;
        $this->isDeviceOutput = false;

        if ($this->arrayMainConfiguration["google"]["siteverfication"] == 1) {
            $this->strGoogleSiteVerification = (file_exists("public/template/parts/googlesiteveri.tpl")) ? $this->Filehandle->readFilecontent("public/template/parts/googlesiteveri.tpl") : null;
        }

        if ($this->arrayMainConfiguration["google"]["googleanalytics"] == 1) {
            $this->strGoogleAnalytics = (file_exists("public/template/parts/googleanalytics.tpl")) ? $this->Filehandle->readFilecontent("public/template/parts/googleanalytics.tpl") : null;
        }

        $this->strHtmlLang = (file_exists("public/template/parts/lang.tpl")) ? $this->Filehandle->readFilecontent("public/template/parts/lang.tpl") : null;

        $this->arrayCurrentSystemEnv["os"] = null;
        $this->arrayCurrentSystemEnv["browser"] = null;
        $this->arrayCurrentSystemEnv["version"] = null;
        $this->arrayCurrentSystemEnv["ismobiledevice"] = false;

        $this->arrayDevices = null;
        if (file_exists('public/configuration/devices.php')) {
            include_once ('public/configuration/devices.php');
            $this->arrayDevices = (sizeof($devices > 0)) ? $devices : null;
        }

        $this->Request = $request;
        $this->Response = $response;
    }

    protected function setPluginRegistryObjects() {
        Registry::set('Mandn', $this->Mandn);
        Registry::set('Filehandle', $this->Filehandle);
        Registry::set('Security', $this->Security);
    }

    public function setDynamicContent($cmd) {
        $this->DynamicSite = (file_exists("public/template/" . $cmd . ".tpl")) ? $this->Filehandle->readFilecontent("public/template/" . $cmd . ".tpl") : null;
    }

    public function replaceLang($cmd) {
        $strHtmlLangNavigation = "";

        if ($this->strHtmlLang != null && file_exists("public/lang/lang.ini")) {
            $arrayLangs = $this->Filehandle->transformIniFileToMutipleArray("public/lang/lang.ini");

            if (sizeof($arrayLangs['lang']) > 0) {
                foreach ($arrayLangs['lang'] as $key => $value) {
                    $strHtmlLangNavigation .= $this->strHtmlLang;
                    $strReplaceCurrentSite = (file_exists("public/lang/package/" . $value . "_" . $cmd . ".json")) ? "lang_" . $value . "_" . $cmd   : "lang_" . $value . "_";
                    $strHtmlLangNavigation = $this->Mandn->setBlock($strHtmlLangNavigation, "LANGID", $strReplaceCurrentSite);
                    $strHtmlLangNavigation = $this->Mandn->setBlock($strHtmlLangNavigation, "LANG", $value);
                }
                
                $strHtmlLangNavigation = $this->Mandn->setBlock($strHtmlLangNavigation, "CURRENT_SITE", $cmd);
            }
        }

        $this->template = $this->Mandn->setBlock($this->template, "LANGNAVIGATION", $strHtmlLangNavigation);
    }

    public function replaceDynamicContent() {
        $this->template = $this->Mandn->setBlock($this->template, "CONTENT", $this->DynamicSite);
    }

    public function replaceMultidesign() {
        $strMultiDesign = "";

        if ($this->arrayMainConfiguration["multidesign"]["activ"] != 0 && sizeof($this->arrayMainConfiguration["multidesign"]["modus"] > 0) && $this->isDeviceOutput === false) {
            $strMultiDesign .= "<script type=\"text/javascript\">\n\tvar string_css = 'defalut';\n";

            foreach ($this->arrayMainConfiguration["multidesign"]["modus"] as $key => $value) {
                $arrayResolutionSplit = explode('x', $key);

                if ($value == 1) {
                    $strMultiDesign .=
                            "\t\tif (screen.width == " . $arrayResolutionSplit[0] . " && screen.height == " . $arrayResolutionSplit[1] . ") { string_css = '" . $arrayResolutionSplit[0] . "x" . $arrayResolutionSplit[1] . "'; }\n";
                }
            }

            $strMultiDesign .= "\tdocument.write('<style type=\"text/css\">@import url(\"/public/css/'+string_css+'.css\");</style>')\n";
            $strMultiDesign .= "\t</script>";
        }

        $this->template = $this->Mandn->setBlock($this->template, "MULTIDESIGN", $strMultiDesign);
    }

    public function replaceMenu($cmd) {
        if (isset($this->arrayMainConfiguration["menustyle"]["file"]) && file_exists("public/template/" . $this->arrayMainConfiguration["menustyle"]["file"]) && sizeof($this->arrayMainConfiguration["menu"]) > 0) {
            $strMenustyle = $this->Filehandle->readFilecontent("public/template/" . $this->arrayMainConfiguration["menustyle"]["file"]);

            foreach ($this->arrayMainConfiguration["menu"] as $menuKey => $arrayMenu) {
                $strMenuName = "menu" . $menuKey;
                $strTemplatePart = $this->Mandn->getPart("LOOP", $strMenuName, $this->template);
                $strMenustyle = (strstr($strTemplatePart, '<option')) ? "selected" : $strMenustyle;
                $this->template = $this->Mandn->removePart("LOOP", $strMenuName, $this->template);
                $strMenu = "";

                foreach ($arrayMenu as $menukey => $menuvalue) {
                    $strMenuPart = $this->Mandn->setBlock($strTemplatePart, "FILENAME", $arrayMenu[$menukey]["filename"]);
                    $strMenuPart = $this->Mandn->setBlock($strMenuPart, "SITE", $arrayMenu[$menukey]["site"]);
                    $strMenuPart = ($arrayMenu[$menukey]["filename"] === $cmd) ?
                            $this->Mandn->setBlock($strMenuPart, "MENU_ACTIV", $strMenustyle) : $this->Mandn->setBlock($strMenuPart, "MENU_ACTIV", "");
                    $strMenu .= $strMenuPart;
                }

                $this->template = $this->Mandn->setBlock($this->template, strtoupper($strMenuName), $strMenu);
            }
        }
    }

    public function replaceSitetitle($cmd) {
        $sitetitle = $cmd;

        if (sizeof($this->arrayMainConfiguration["sitetitle"]) > 0) {
            foreach ($this->arrayMainConfiguration["sitetitle"] as $site => $title) {
                if ($site === $cmd) {
                    $sitetitle = $title;
                    break;
                }
            }
        }

        $this->template = $this->Mandn->setBlock($this->template, "SITETITLE", $sitetitle);
    }

    public function dynamicFileIntegration($cmd) {
        if (isset($this->arrayMainConfiguration["dynamicFiles"]) && $this->arrayCurrentSystemEnv["ismobiledevice"] === false) {
            $this->template =
                    $this->template = $this->Mandn->replaceDynamicFilesIntegration($this->template, $cmd, $this->arrayMainConfiguration["dynamicFiles"], $this->Request->getdocumentRoot());
        }

        if (isset($this->arrayMainConfiguration["dynamicDeviceFiles"]) && $this->arrayCurrentSystemEnv["ismobiledevice"] === true) {
            $this->template
                    = $this->Mandn->replaceDynamicFilesIntegration($this->template, $cmd, $this->arrayMainConfiguration["dynamicDeviceFiles"], $this->Request->getdocumentRoot());
        }
    }

    public function buildPlugins() {
        $strPluginTemplate = "";

        if (sizeof($this->arrayExtensions) > 0) {
            foreach ($this->arrayExtensions as $class => $arrayOptions) {
                $hasObjInstance = false;
                if (sizeof($arrayOptions) > 0) {
                    foreach ($arrayOptions as $optionKey => $options) {
                        if (is_array($options)) {
                            foreach ($options as $markerKey => $markerValue) {
                                $strSearchInput = "ext:" . strtoupper($markerValue);

                                if (strstr($this->template, $strSearchInput) && $arrayOptions["active"] == 1) {
                                    if ($hasObjInstance === false) {
                                        $hasObjInstance = true;
                                        $this->template = $this->Mandn->replaceDynamicFilesIntegration($this->template, false, "public/extensions/" . $class . "/css/" . $class . ".css", $this->Request->getdocumentRoot());
                                        $this->template = $this->Mandn->replaceDynamicFilesIntegration($this->template, false, "public/extensions/" . $class . "/js/" . $class . ".js", $this->Request->getdocumentRoot());
                                        if ($class == "Contact") {
                                            $configuration = (file_exists("public/extensions/" . $class . "/configuration/" . $class . ".ini")) ? $this->Filehandle->transformIniFileToMutipleArray("public/extensions/" . $class . "/configuration/" . $class . ".ini") : null;
                                        } else {
                                            $configuration = (file_exists("public/extensions/" . $class . "/configuration/" . $class . ".json")) ?
                                                    json_decode($this->Filehandle->readFilecontent("public/extensions/" . $class . "/configuration/" . $class . ".json")) : null;
                                        }

                                        if (isset($configuration->dynamicFiles)) {
                                            $this->template = $this->Mandn->replaceDynamicFilesIntegration($this->template, false, $configuration->dynamicFiles, $this->Request->getdocumentRoot());
                                        }

                                        $strClass = 'delightnet\\extensions\\' . $class . '\\' . $class;
                                        $strStartClass = "start" . $class;

                                        if (!($class instanceof $strClass)) {
                                            $objClass = new $strClass($configuration);
                                        }
                                    }

                                    $strPluginTemplate = $objClass->$strStartClass($optionKey, $this->Request);
                                    $this->template = $this->Mandn->setBlock($this->template, $strSearchInput, $strPluginTemplate);
                                    break;
                                } else {
                                    $this->template = $this->Mandn->setBlock($this->template, $strSearchInput, "");
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    public function setGlobalEnv() {
        $ua = $this->Request->getHttpUserAgent();
        $shorty = '';
        $version = null;

        // Operating system
        foreach ($this->arrayDevices['os'] as $k => $v) {
            if (stripos($ua, $k) !== false) {
                $this->arrayCurrentSystemEnv["os"] = $v['os'];
                $this->arrayCurrentSystemEnv["ismobiledevice"] = $v['mobile'];
                break;
            }
        }

        // Browser and version
        foreach ($this->arrayDevices['browser'] as $k => $v) {
            if (stripos($ua, $k) !== false) {
                $this->arrayCurrentSystemEnv["browser"] = $v['browser'];
                $shorty = $v['shorty'];
                $version = preg_replace($v['version'], '$1', $ua);
                break;
            }
        }

        $versions = explode('.', $version);
        if (isset($versions[0])) {
            $this->arrayCurrentSystemEnv["version"] = $versions[0];
        }
        
        // switch to devices-output
        // $this->arrayCurrentSystemEnv["ismobiledevice"] = true;
    }

    public function writeGlobalEnv($cmd) {
        $this->template = $this->Mandn->setBlock($this->template, "STR_OS", $this->arrayCurrentSystemEnv["os"]);
        $this->template = $this->Mandn->setBlock($this->template, "STR_BROWSER", $this->arrayCurrentSystemEnv["browser"]);
        $this->template = $this->Mandn->setBlock($this->template, "STR_VERSION", $this->arrayCurrentSystemEnv["version"]);
        $this->template = $this->Mandn->setBlock($this->template, "STR_CURRENT_COMMAND", $cmd);
    }

    public function setGoogle() {
        if ($this->strGoogleSiteVerification != null && $this->arrayMainConfiguration["google"]["siteverfication"] == 1) {
            $this->strGoogleSiteVerification = $this->Mandn->setBlock($this->strGoogleSiteVerification, "VERIFICATIONID", $this->arrayMainConfiguration["google"]["siteverficationId"]);
            $this->template = $this->Mandn->setBlock($this->template, "GOOGLESITEVERIFICATION", $this->strGoogleSiteVerification);
        } else {
            $this->template = $this->Mandn->setBlock($this->template, "GOOGLESITEVERIFICATION", "");
        }

        if ($this->strGoogleAnalytics != null && $this->arrayMainConfiguration["google"]["googleanalytics"] == 1) {
            $this->strGoogleAnalytics = $this->Mandn->setBlock($this->strGoogleAnalytics, "GOOGLE_ANALYTICS_SITEID", $this->arrayMainConfiguration["google"]["googleanalyticsId"]);
            $this->template = $this->Mandn->setBlock($this->template, "GOOGLEANALYTICS", $this->strGoogleAnalytics);
        } else {
            $this->template = $this->Mandn->setBlock($this->template, "GOOGLEANALYTICS", "");
        }
    }

    public function writeSystemsettings() {
        if (isset($this->arraySystemSettings['systemsettings']['systeminfo']) && $this->arraySystemSettings['systemsettings']['systeminfo'] == 1) {
            $strMessage = "alert('Devices:   Betriebs-System: " . $this->arrayCurrentSystemEnv["os"] . ",  Browser: " . $this->arrayCurrentSystemEnv["browser"] .
                    ",  Browser-Version: " . $this->arrayCurrentSystemEnv["version"] . "');";

            $this->template = $this->Mandn->setBlock($this->template, "OUTPUT_SYSTEMINFO", $strMessage);
        } else {
            $this->template = $this->Mandn->setBlock($this->template, "OUTPUT_SYSTEMINFO", "");
        }
    }

    public function writeFormSendedIdent() {
        if (strstr($this->template, '{SENDED_IDENT}')) {
            $this->template = $this->Mandn->setBlock($this->template, "SENDED_IDENT", "formSendedSuccess");
        }
    }

    public function buildTemplate() {
        $cmd = $this->Security->undoTags($this->Request->getParameter('cmd'));
        $this->setPluginRegistryObjects();
        $this->setDynamicContent($cmd);
        $this->setGlobalEnv();
        $this->setGoogle();

        $this->writeGlobalEnv($cmd);

        if (!file_exists("public/template/" . $cmd . ".tpl")) {
            $cmd = $this->arrayMainConfiguration["homesite"]["file"];
            $this->Response->addHeader("location", $cmd . ".html");
        }

        $this->replaceSitetitle($cmd);
        $this->replaceMenu($cmd);
        $this->replaceMultidesign();
        $this->dynamicFileIntegration($cmd);

        $this->replaceDynamicContent();
        $this->replaceLang($cmd);
        $this->buildPlugins();
        $this->writeSystemsettings();
        
        $this->template = $this->Mandn->setBlock($this->template, "DYNAMICFILEINTEGRATION", "");
        $this->writeSystemsettings();
        $this->writeFormSendedIdent();
        $this->Response->write($this->template);
    }

}