<?php

/*
 * basic superclass template for building classic html-sites and device-templates
 * 
 * @author    Gunnar von Spreckelsen <service@ihrwebentwickler.de>
 * @package   delightos
 * 
 */

namespace delightnet\delightos;

class FrontendTemplate extends Template implements TemplateView {
    public $arrExtensions;

    public $strGooglesiteverification;
    public $strGoogleAnalytics;

    /**
     * buid Env for extension-integration and -development
     */
    public function setPluginEnv() {
        if (!empty($this->arrExtensions['extensions'])) {
            // include global ext-env
            foreach ($this->arrExtensions['extensions'] as $strExtName => $arrExt) {
                // include optional ext-env
                if (!empty($arrExt["Marker"])) {
                    $arrayClass = explode("_", $strExtName);
                    $strClassCamelCase = "";

                    foreach ($arrayClass as $objExtPart => $valueClassPart) {
                        $strClassCamelCase .= ucwords(strtolower($valueClassPart));
                    }

                    $strNamespace = 'delightnet\\extensions\\' . strtolower($strClassCamelCase) . '\\';

                    if (
                        file_exists("public/extensions/" . strtolower($strClassCamelCase) . "/configuration/" . strtolower($strClassCamelCase) . ".json")
                        && $this->arrExtensions['extensions'][$strExtName]["isFrontendExt"] === false
                    ) {
                        $jsonConfiguration = $this->Filehandle->readFilecontent("public/extensions/" . strtolower($strClassCamelCase) . "/configuration/" . strtolower($strClassCamelCase) . ".json");
                        $jsonConfiguration = $this->MandN->deleteControlCharactersAndWhitespaceFromString($jsonConfiguration);
                        $objConfiguration = json_decode($jsonConfiguration);
                    } else {
                        $objConfiguration = null;
                    }

                    $strClassPath = $strNamespace . $strClassCamelCase . "Controller";
                    $strObj = $strClassCamelCase . "Controller";

                    // load ext-template-file and replace Instance-Id
                    $strExtTemplateNonInstanced = (file_exists("public/extensions/" . strtolower($strClassCamelCase) . "/template/" . strtolower($strClassCamelCase) . ".tpl")) ?
                        $this->Filehandle->readFilecontent("public/extensions/" . strtolower($strClassCamelCase) . "/template/" . strtolower($strClassCamelCase) . ".tpl") : "";

                    foreach ($arrExt["Marker"] as $keyMarker => $valueMarker) {
                        $strSearchInput = "EXT:" . strtoupper($valueMarker);

                        if (
                            strpos($this->template, $strSearchInput) !== false && $this->arrExtensions['extensions'][$strExtName]["isActive"] === true
                        ) {
                            if ($keyMarker == 0) {
                                // replace js-ext-files
                                if (is_dir("public/extensions/" . strtolower($strClassCamelCase) . "/js/") === true) {
                                    $arrayJsExtFiles = $this->Filehandle->readDirectoryNonRecursive("public/extensions/" . strtolower($strClassCamelCase) . "/js/", true);
                                    if (!empty($arrayJsExtFiles)) {
                                        $this->template = $this->MandN->replaceDynamicLinks($this->template, $arrayJsExtFiles);
                                    }
                                }

                                // replace css-ext-files
                                if (is_dir("public/extensions/" . strtolower($strClassCamelCase) . "/css/") === true) {
                                    $arrayCssExtFiles = $this->Filehandle->readDirectoryNonRecursive("public/extensions/" . strtolower($strClassCamelCase) . "/css/", true);
                                    if (!empty($arrayCssExtFiles)) {
                                        $this->template = $this->MandN->replaceDynamicLinks($this->template, $arrayCssExtFiles);
                                    }
                                }
                            }

                            $strExtTemplate = $this->MandN->setBlock($strExtTemplateNonInstanced, "INSTANCEID", $keyMarker + 1);

                            if (!$strObj instanceof $strClassPath) {
                                $objExt = new $strClassPath();
                                $objExt->setController($strClassPath . strtolower($strClassCamelCase), $keyMarker + 1, $strExtTemplate, $objConfiguration, $this->arrLangs,
                                    $this->strAlpha2 , $this->objRequest, $this->Filehandle, $this->MandN, $this->Security, $this->Session);
                            }
                            $this->template = $this->MandN->setBlock($this->template, $strSearchInput, $objExt->action());
                        } else {
                            $this->template = $this->MandN->setBlock($this->template, $strSearchInput, "");
                        }
                    }
                }
            }
        }
    }

    /**
     * HTML-replace in template of global-JS-Object
     */
    public function replaceGlobalEnv() {
        $this->template = $this->MandN->setBlock($this->template, "OS", $this->arrSystemEnv["os"]);
        $this->template = $this->MandN->setBlock($this->template, "BROWSER", $this->arrSystemEnv["browser"]);
        $this->template = $this->MandN->setBlock($this->template, "VERSION", $this->arrSystemEnv["version"]);
        $this->template = $this->MandN->setBlock($this->template, "IS_MOBILE_DEVICE", $this->arrSystemEnv["isDevice"]);

        $this->template = $this->MandN->setBlock($this->template, "CURRENT_COMMAND", $this->cmd);
        $this->template = $this->MandN->setBlock($this->template, "CURRENT_LANG", $this->strAlpha2);
    }

    /**
     * HTML-replace in template with google-site-verfification and -analytics
     */
    public function replaceGoogle() {
        $this->strGooglesiteverification = (file_exists("public/template/parts/googlesiteverification.tpl")) ? $this->Filehandle->readFilecontent("public/template/parts/googlesiteverification.tpl") : "";
        $this->strGoogleanalytics = (file_exists("public/template/parts/googleanalytics.tpl")) ? $this->Filehandle->readFilecontent("public/template/parts/googleanalytics.tpl") : "";

        if ($this->strGooglesiteverification != "" && $this->arrMainConfiguration['main']["google"]["activateSiteverfication"] == 1 && isset($this->arrMainConfiguration["google"]["siteverficationId"])) {
            $this->strGooglesiteverification = $this->MandN->setBlock($this->strGooglesiteverification, "SITE_VERIFICATION_ID", $this->arrMainConfiguration["google"]["siteverficationId"]);
            $this->template = $this->MandN->setBlock($this->template, "GOOGLESITEVERIFICATION", $this->strGooglesiteverification);
        } else {
            $this->template = $this->MandN->setBlock($this->template, "GOOGLESITEVERIFICATION", "");
        }

        if ($this->strGoogleanalytics != null && $this->arrMainConfiguration['main']["google"]["activateGoogleanalytics"] == 1 && isset($this->arrMainConfiguration['main']["google"]["googleanalyticsId"])) {
            $this->strGoogleanalytics = $this->MandN->setBlock($this->strGoogleanalytics, "GOOGLE_ANALYTICS_ID", $this->arrMainConfiguration["google"]["googleanalyticsId"]);
            $this->template = $this->MandN->setBlock($this->template, "GOOGLEANALYTICS", $this->strGoogleanalytics);
        } else {
            $this->template = $this->MandN->setBlock($this->template, "GOOGLEANALYTICS", "");
        }
    }

    /**
     * main-method to call templating-building-methods from stack
     */
    public function buildTemplate() {
        $this->setCmd();
        $this->setAction();
        $this->setLangEnv();
        $this->setThemeEnv();
        $this->setDynamicSite();
        $this->setGlobalEnv();

        if (!empty($this->arrMainConfiguration['main']["dynamicFiles"])) {
            foreach ($this->arrMainConfiguration['main']["dynamicFiles"] as $arrayDynamicFiles) {
                $this->template = $this->MandN->replaceDynamicLinks($this->template, $arrayDynamicFiles);
            }
        }

        $this->replaceGoogle();
        $this->replaceHtmlData("sitetitle");
        $this->replaceHtmlData("breadcrumb");
        $this->replaceHtmlData("description");
        $this->replaceLang();
        $this->replaceMultidesign();
        $this->replaceDynamicFiles();

        $this->replaceGlobalEnv();
        $this->replaceMenu();
        $this->setPluginEnv();
        $this->replaceLanguageTexts();

        $this->template = $this->MandN->setBlock($this->template, "DYNAMICFILEINTEGRATION", "");
        $this->objResponse->write($this->template);
    }
}