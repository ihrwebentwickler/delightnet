<?php

/*
 * very small own template-language, a bit like smarty, but MandN tasts good to
 * 
 * @author    Gunnar von Spreckelsen <service@ihrwebentwickler.de>
 * @package   delightos
 * @version   3.00
 * 
 */

namespace delightnet\delightos;

class MandN {

    public $Filehandle;

    public function __construct() {
        $this->Filehandle = new \delightnet\delightos\Filehandle();
    }

    public function setBlock($strContent, $blockname, $strValue) {
        $strContent = str_replace("{" . $blockname . "}", $strValue, $strContent);

        return $strContent;
    }

    public function deleteControlCharactersFromString($strText) {
        $strText = preg_replace("/\r|\n/s", "<br>", $strText);
        return $strText;
    }

    public function getPart($strPartIdentifier, $strPartName, $strContent) {
        $strPartBegin = "{" . strtoupper($strPartIdentifier) . " " . $strPartName . "}";
        $strPartEnd = "{/" . strtoupper($strPartIdentifier) . " " . $strPartName . "}";

        $arrayParts = explode($strPartBegin, $strContent);

        if (!isset($arrayParts[1])) {
            $strPart = "";
        } else {
            $strPart = substr($arrayParts[1], 0, strpos($arrayParts[1], $strPartEnd));
        }

        return $strPart;
    }
    
    public function removePart($strPartIdentifier, $strPartName, $strContent) {
        $strRegExpr = '#{' . strtoupper($strPartIdentifier) . ' ' . $strPartName . '}.*.{/' . strtoupper($strPartIdentifier) . ' ' . $strPartName . '}#s';
        $strContent = preg_replace($strRegExpr, "{" . strtoupper($strPartName) . "}", $strContent);

        return $strContent;
    }
    
    public function replaceDynamicFilesIntegration($template, $cmd, $IntegrationFiles, $documentRoot) {
        $strDynamicFilesIntegration = "";
        $arrayIntegrationFileExtensions = array("css", "js");

        if (!is_array($IntegrationFiles)) {
            $arrayIntegrationFiles[0]['file'] = $IntegrationFiles;
        } else {
            $arrayIntegrationFiles = $IntegrationFiles;
        }

        foreach ($arrayIntegrationFiles as $key => $arrayFiles) {
            foreach ($arrayFiles as $filekey => $filename) {
                foreach ($arrayIntegrationFileExtensions as $extkey => $extvalue) {
                    if (strstr($filename, "." . $extvalue) && file_exists($documentRoot . "public/template/parts/include_" . $extvalue . ".tpl")) {
                        if (($cmd === false) || ($filekey == "template") || ($cmd !== false && $cmd == $filekey && file_exists($documentRoot . "public/template/" . $cmd . ".tpl"))) {
                            $fileContent = $this->Filehandle->readFilecontent($documentRoot . "public/template/parts/include_" . $extvalue . ".tpl") . "\n";
                            $strDynamicFilesIntegration .= $this->setBlock($fileContent, "SRC_" . strtoupper($extvalue), $filename);
                        }
                    }
                }
            }
        }

        $strDynamicFilesIntegration .= "{DYNAMICFILEINTEGRATION}\n";
        $template = $this->setBlock($template, "DYNAMICFILEINTEGRATION", $strDynamicFilesIntegration);

        return $template;
    }

}