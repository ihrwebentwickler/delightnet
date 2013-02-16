<?php

/*
 * Read and write GUI-Data of html-dom
 * 
 * @author    Gunnar von Spreckelsen <service@ihrwebentwickler.de>
 * @package   delightcms
 * @version   1.00
 * 
 */

namespace delightnet\delightcms;

class Gui {

    public $strMenuOption;
    public $Filehandle;
    public $Mandn;
    public $strOptionGroupEntry;

    public function __construct($Filehandle, $Mandn, $Security) {
        $this->Filehandle = $Filehandle;
        $this->Mandn = $Mandn;
        $this->Security = $Security;

        ob_start();
        include ("template/parts/gui_input_del.tpl");
        $this->InputDelHtml = ob_get_contents();
        ob_end_clean();

        $this->strOptionEntry = (file_exists("../cmsadmin/template/parts/menuEntry.tpl")) ? $this->Filehandle->readFilecontent("../cmsadmin/template/parts/optionEntry.tpl") : "";
        $this->strOptionGroupEntry = (file_exists("../cmsadmin/template/parts/optionGroupEntry.tpl")) ? $this->Filehandle->readFilecontent("../cmsadmin/template/parts/optionGroupEntry.tpl") : "";
    }

    /**
     * read correct extension-template file
     *  
     * @param string $extSystemName
     * @return string $strExtconf
     */
    public function readExtConfFile($extSystemName) {
        $strExtconf = "";

        if (file_exists("../delightnet/extensions/" . $extSystemName . "/cmsadmin/" . $extSystemName . ".tpl")) {
            $strExtconf = $this->Filehandle->readFilecontent("../delightnet/extensions/" . $extSystemName . "/cmsadmin/" . $extSystemName . ".tpl");
        } elseif (file_exists("../cmsadmin/template/parts/extconf.tpl")) {
            $strExtconf = $this->Filehandle->readFilecontent("../cmsadmin/template/parts/extconf.tpl");
        }

        return $strExtconf;
    }

    public function replaceDomDataInExtTemplate($extSystemName) {
        $strDomSelect = (file_exists("../cmsadmin/template/parts/guiExt/select.tpl")) ? $this->Filehandle->readFilecontent("../cmsadmin/template/parts/guiExt/select.tpl") : "";
        $strDomOption = (file_exists("../cmsadmin/template/parts/guiExt/option.tpl")) ? $this->Filehandle->readFilecontent("../cmsadmin/template/parts/guiExt/option.tpl") : "";
        $strJsonExt = (file_exists("../public/extensions/" . $extSystemName . "/configuration/" . $extSystemName . ".json")) ?
                $this->Filehandle->readFilecontent("../public/extensions/" . $extSystemName . "/configuration/" . $extSystemName . ".json") : "";
        $strXml = (file_exists("../delightnet/extensions/" . $extSystemName . "/" . $extSystemName . ".xml")) ?
                $this->Filehandle->readFilecontent("../delightnet/extensions/" . $extSystemName . "/" . $extSystemName . ".xml") : "";

        $strJsonExt = $this->Filehandle->cleanJsonStr($strJsonExt);
        $arrayExt = json_decode($strJsonExt);
        $strDomOrigin = $this->readExtConfFile($extSystemName);

        $strDom = "";
        $strInstance = "";
        $strDomSelectInstance = "";
        $strDomOriginCopy = "";

        $objXml = simplexml_load_string($strXml);
        foreach ($objXml->xpath("/document/" . $extSystemName) as $xmlInstances) {
            foreach ($xmlInstances as $instanceNode => $instanceChilds) {
                $strDomOriginCopy .= $this->Mandn->getPart("PART", strtoupper($instanceNode), $strDomOrigin);
                foreach ($instanceChilds as $childValue) {
                    $strDomOriginCopy = $this->Mandn->setBlock($strDomOriginCopy, "INSTANCEKEY", -2);
                    $strDomOriginCopy = $this->Mandn->setBlock($strDomOriginCopy, "KEY1", -1);
                    $strDomOriginCopy = $this->Mandn->setBlock($strDomOriginCopy, strtoupper($childValue->getName()), $childValue);
                }
            }
        }

        foreach ($arrayExt as $instanceKey => $instances) {
            foreach ($instances as $contentKey => $contentArray) {
                foreach ($contentArray as $itemKey => $items) {
                    if ($contentKey === $extSystemName) {
                        $strDomSelectInstance.= $strDomOption;
                        $strDomSelectInstance = $this->Mandn->setBlock($strDomSelectInstance, "GUI_OPTION_VALUE", $contentKey . " " . $itemKey);
                        $strDomSelectInstance = $this->Mandn->setBlock($strDomSelectInstance, "GUI_OPTION_ID", $itemKey) . "\n";
                        $strDomSelectInstance = $this->Mandn->setBlock($strDomSelectInstance, "GUI_CONTENTIDENTIFIER", $contentKey);

                        $strInstance .= $this->Mandn->getPart("PART", strtoupper($contentKey), $strDomOrigin);
                        $strInstance = $this->Mandn->setBlock($strInstance, "INSTANCEKEY", $itemKey);
                    }

                    foreach ($items as $entriesKey => $entries) {
                        if (!is_object($entries)) {
                            $strInstance = $this->Mandn->setBlock($strInstance, strtoupper($entriesKey), $entries);
                        } else {
                            $strDom .= $this->Mandn->getPart("PART", strtoupper($contentKey), $strDomOrigin);
                            $strDom = $this->Mandn->setBlock($strDom, "INSTANCEKEY", $itemKey);
                            $strDom = $this->Mandn->setBlock($strDom, "KEY1", $entriesKey);

                            foreach ($entries as $entryKey => $entry) {
                                $strDom = $this->Mandn->setBlock($strDom, strtoupper($entryKey), $entry);
                            }
                        }
                    }
                }
            }
        }

        $strDomSelect = $this->Mandn->setBlock($strDomSelect, "GUI_SELECT_NAME", $extSystemName) . "\n";
        $strDomSelect = $this->Mandn->setBlock($strDomSelect, "GUI_SELECT_OPTION_LIST", $strDomSelectInstance) . "\n";

        $strDomOrigin = $this->Mandn->setBlock($strDomOrigin, "SELECT_INSTANCES", $strDomSelect) . "\n";
        $strDomOrigin = $this->Mandn->setBlock($strDomOrigin, "EXT_SYSTEMNAME", $extSystemName);

        $strDom = $strDomOriginCopy . $strInstance . $strDom;
        $strDomOrigin = $this->Mandn->removePart("PART", "extinstance", $strDomOrigin);
        $strDomOrigin = $this->Mandn->setBlock($strDomOrigin, "EXTINSTANCE", $strDom);

        return $strDomOrigin;
    }

    /**
     * get editable/ non-editable files of content-sites like css- or tpl-files.
     * a simple algorithm reads the content-string. if the content-string includes
     * a textarea f.e. the site is marked as no-editable
     * 
     *  
     * @param array $arrayBlockedFiles
     * @param array $arrayContentFolders
     * @param int $intBan
     * @return string $strOptionGroup
     */
    public function getSelectBoxOfEditableFiles($arrayBlockedFiles, $arrayContentFolders, $intBan) {
        /*
         * Banner-Value
         * 0 : all files from directory
         * 1 : all files from directory, but without banned files
         * 2 : only banned files from directory
         */

        if (!is_array($arrayBlockedFiles)) {
            $arrayBlockedFiles[0] = $arrayBlockedFiles;
        }

        $strOptionGroup = "";

        foreach ($arrayContentFolders as $labelKey => $arrayLabelDetails) {
            $iterator = new \DirectoryIterator($arrayLabelDetails["link"]);
            $arrayFileNames = $this->Filehandle->readDirectoryNonRecursive($iterator);
            $strOptionEntries = "";
            $boolContent = false;
            $boolScaffolding = false;

            foreach ($arrayFileNames as $filenameKey => $filenameValue) {
                $strLabel = "";

                if ($intBan == 0 || !isset($intBan)) {
                    $boolContent = true;
                }

                if ($intBan == 1) {
                    $strTestOfFileBan = (file_exists($arrayLabelDetails["link"] . "/" . $filenameValue)) ? $this->Filehandle->readFilecontent($arrayLabelDetails["link"] . "/" . $filenameValue) : "";

                    if ($strTestOfFileBan != "" && !preg_match("/\binput\b | \btextarea\b/i", $strTestOfFileBan) && !in_array($filenameValue, $arrayBlockedFiles)) {
                        $boolContent = true;
                    }
                }

                if ($intBan == 2) {
                    $strTestOfFileBan = (file_exists($arrayLabelDetails["link"] . "/" . $filenameValue)) ? $this->Filehandle->readFilecontent($arrayLabelDetails["link"] . "/" . $filenameValue) : "";

                    if (preg_match("/\binput\b | \btextarea\b/i", $strTestOfFileBan) || in_array($filenameValue, $arrayBlockedFiles)) {
                        $boolContent = true;
                    }
                }

                if ($boolContent === true && $boolScaffolding === false) {
                    $strOptionGroup .= $this->strOptionGroupEntry;
                    $boolScaffolding = true;
                }

                if ($boolContent === true) {
                    $strOptionEntries .= $this->strOptionEntry;
                    $strOptionEntries = $this->Mandn->setBlock($strOptionEntries, "FILENAME_CONTENTSITE", $filenameValue);
                    $strOptionEntries = $this->Mandn->setBlock($strOptionEntries, "FILELINK_CONTENTSITE", $arrayLabelDetails["link"] . "/" . $filenameValue);
                    $strLabel = $arrayLabelDetails["link"];
                    $boolContent = false;
                }
            }

            if ($strOptionGroup != "") {
                $strOptionGroup = $this->Mandn->setBlock($strOptionGroup, "OPTGROUPID", $labelKey);
                $strOptionGroup = $this->Mandn->setBlock($strOptionGroup, "LABEL", $labelKey);
                $strOptionGroup = $this->Mandn->setBlock($strOptionGroup, "OPTIONS", $strOptionEntries);
            }
        }

        return $strOptionGroup;
    }

    /**
     * if a content is presented in the dialog-editor, the dialog-editor-frame-dom-content is replaced to
     * the template-dom
     *  
     * @param string $template
     * @return string $template
     */
    public function writeStandardDialogEditor($template) {
        if (strpos($template, "DIALOGEDITOR") === false) {
            return $template;
        }

        $strDialogEditor = (file_exists("../cmsadmin/template/dialogeditor.tpl")) ? $this->Filehandle->readFilecontent("../cmsadmin/template/dialogeditor.tpl") : "";
        $template = $this->Mandn->setBlock($template, "DIALOGEDITOR", $strDialogEditor);

        return $template;
    }

    /**
     * set a hex-color-value to the rgb-format
     *  
     * @param string $strRgb
     * @return array
     */
    public function hex2rgb($strRgb) {
        return array(
            hexdec(substr($strRgb, 0, 2)),
            hexdec(substr($strRgb, 2, 2)),
            hexdec(substr($strRgb, 4, 2)),
        );
    }

    /**
     * decode array to json-object
     *  
     * @param string $filelinkExt
     * @param array $arrayData
     * @return bool $isSaveError
     */
    public function jsonDecode($arrayData) {
        // $arrayData = $this->Filehandle->rebuildArrayToFullJsonUtfRepresentation($arrayData);
        $strJson = json_encode($arrayData, JSON_HEX_APOS);
        $strJson = $this->Filehandle->cleanJsonStr($strJson);

        return $strJson;
    }
}