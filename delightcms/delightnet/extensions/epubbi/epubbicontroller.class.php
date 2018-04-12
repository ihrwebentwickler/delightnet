<?php

/*
 * Blogger, a simple-Blog-System,
 * 
 * @author    Gunnar von Spreckelsen <service@ihrwebentwickler.de>
 * @package   extension
 * @version   1.10
 * 
 */

namespace delightnet\extensions\Epubbi;
use delightnet\delightos\Controller;

class EpubbiController extends Controller {
    public function action() {
        $objEpubbi = new Epubbi($this->strAlpha2, $this->MandN, $this->Filehandle);
        if ($this->objRequest->getParameter("submitEpubbi") && $this->objRequest->getParameter("epubbisite")) {
            $arrayHtmlEpubbiData = $this->Security->undoTags($this->objRequest->getParameter('epubbisite'));

            if (sizeof($arrayHtmlEpubbiData) > 0) {
                $objEpubbi->copyEbookscaffold();
                $objEpubbi->buildEbookContent($this->objConfiguration, $arrayHtmlEpubbiData);

                $this->Filehandle->getZipArchiv("Ebook_" . $this->objRequest->getHttpHost() . ".epub", "public/extensions/epubbi/tmp");
            }
        }

        $arrayChapters = $objEpubbi->getChapters();
        $strEbookFileSelectList = $this->replaceEbookFileSelectList($arrayChapters);
        $this->strExtTemplate = $this->MandN->setBlock($this->strExtTemplate, "EPUBBI_CHECKBOXLIST", $strEbookFileSelectList);
        $this->strExtTemplate = $this->MandN->setBlock($this->strExtTemplate, "CURRENT_LANG", $this->strAlpha2);

        return $this->strExtTemplate;
    }

    public function replaceEbookFileSelectList($arrEbookFiles) {
        $strItemSelectList = (file_exists("public/extensions/epubbi/template/parts/checkboxlist.tpl")) ?
            $this->Filehandle->readFilecontent("public/extensions/epubbi/template/parts/checkboxlist.tpl") : "";
        $strEbookFileSelectList = "";

        foreach ($arrEbookFiles["filelink"] as $key => $value) {
            $strEbookFileSelectList .= $strItemSelectList . "\n";
            $strEbookFileSelectList = $this->MandN->setBlock($strEbookFileSelectList, "EPUBBI_PARTS_FILELINK", $arrEbookFiles["filelink"][$key]);
        }

        return $strEbookFileSelectList;
    }
}