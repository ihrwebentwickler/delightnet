<?php

/*
 * copy and generate ebook-data
 *
 * @author    Gunnar von Spreckelsen <service@ihrwebentwickler.de>
 * @package   extension
 * @version   1.10
 *
 */

namespace delightnet\extensions\Epubbi;

class Epubbi {
    public $arrClosedSites;

    public $strAlpha2;
    public $MandN;
    public $Filehandle;

    public function __construct($strAlpha2, $MandN, $Filehandle) {
        $this->strAlpha2 = $strAlpha2;
        $this->MandN = $MandN;
        $this->Filehandle = $Filehandle;

        $this->arrClosedSites = (file_exists("public/extensions/epubbi/configuration/closedsites.json")) ?
            json_decode($this->Filehandle->readFilecontent("public/extensions/epubbi/configuration/closedsites.json"), true) : null;
    }

    public function getChapters() {
        $arrFiles = $this->Filehandle->readDirectoryNonRecursive("public/template/", true);

        if(count(glob("public/template/lang/" . $this->strAlpha2 . "/")) > 0) {
            $arrFilesLang = $this->Filehandle->readDirectoryNonRecursive("public/template/lang/" . $this->strAlpha2 . "/", true, array('json'));
        }

        if ($arrFilesLang !== null) {
            $arrFiles = array_merge($arrFiles, $arrFilesLang);
        }

        $arrFiles = array_diff($arrFiles, $this->arrClosedSites['closedsites']);

        $arrMenuentries = (file_exists("public/template/lang/" . $this->strAlpha2 . "/menuentry.json")) ?
            json_decode($this->Filehandle->readFilecontent("public/template/lang/" . $this->strAlpha2 . "/menuentry.json"), true) : null;

        foreach ($arrFiles as $filekey => $filename) {
            if (file_exists($filename)) {
                $arrEbookFiles["filelink"][] = $filename;
                $arrEbookFiles["menuentry"][] = (isset($arrMenuentries["menuentry"][explode(".tpl", $filename)[0]])) ?
                    $arrMenuentries["menuentry"][explode(".tpl", $filename)[0]] : "";
            }
        }

        return $arrEbookFiles;
    }

    public function buildEbookContent($objConfiguration, $arraySites) {
        // 0: load "public/extensions/epubbi/ebooktpl/template.tpl" as ebook-scaffold and HTML-Header-Text
        $strEbookScaffoldContent = (file_exists("public/extensions/epubbi/ebooktpl/template.tpl")) ?
            $this->Filehandle->readFilecontent("public/extensions/epubbi/ebooktpl/template.tpl") : "";
        $htmlChapterHeaderOrigin = (file_exists("public/extensions/epubbi/ebooktpl/parts/header.tpl")) ?
            $this->Filehandle->readFilecontent("public/extensions/epubbi/ebooktpl/parts/header.tpl") : "";

        if ($objConfiguration) {
            // 1.) content.opf
            $filelink = "public/extensions/epubbi/tmp/OEBPS/content.opf";
            if (file_exists($filelink)) {
                $content_opf = $this->Filehandle->readFilecontent($filelink);
                $content_opf = $this->MandN->setBlock($content_opf, "EPUBBI_PACKAGE_LANGUAGE", $this->strAlpha2);
                $content_opf = $this->MandN->setBlock($content_opf, "EPUBBI_PACKAGE_DATE", date("Y-m-d"));
                $content_opf = $this->MandN->setBlock($content_opf, "EPUBBI_PACKAGE_TITLE", $objConfiguration->epubbi->epubbiMain->{1}->title . " [" . $this->strAlpha2 . "]");
                $content_opf = $this->MandN->setBlock($content_opf, "EPUBBI_PACKAGE_AUTHOR", $objConfiguration->epubbi->epubbiMain->{1}->author);
                $content_opf = $this->MandN->setBlock($content_opf, "EPUBBI_PACKAGE_DESCRIPTION", $objConfiguration->epubbi->epubbiMain->{1}->description);
                $this->Filehandle->writeFilecontent($filelink, $content_opf);
            }

            // 2.) toc.ncx
            $filelink = "public/extensions/epubbi/tmp/OEBPS/toc.ncx";
            if (file_exists($filelink)) {
                $toc_ncx = $this->Filehandle->readFilecontent($filelink);
                $toc_ncx = $this->MandN->setBlock($toc_ncx, "EPUBBI_TOC_TITLE", $objConfiguration->epubbi->epubbiMain->{1}->title . " [" . $this->strAlpha2 . "]");
                $toc_ncx = $this->MandN->setBlock($toc_ncx, "EPUBBI_TOC_AUTHOR", $objConfiguration->epubbi->epubbiMain->{1}->author);
                $this->Filehandle->writeFilecontent($filelink, $toc_ncx);
            }

            // 3.) content.xhtml
            $filelink = "public/extensions/epubbi/tmp/OEBPS/content.xhtml";
            $strEbookContent = "";

            if (file_exists($filelink)) {
                foreach ($arraySites as $key => $strTemplateFileLink) {
                    $arrFilename = explode("/", $strTemplateFileLink);
                    $htmlChapter = $htmlChapterHeaderOrigin;
                    $htmlChapter = $this->MandN->setBlock($htmlChapter, "EBOOKTPL_TEMPLATE_PART_CHAPTER", $strTemplateFileLink);
                    $htmlChapter = $this->MandN->setBlock($htmlChapter, "EBOOKTPL_TEMPLATE_PART_CHAPTERNB", $key + 1);

                    $strEbookContentDirty = (file_exists($strTemplateFileLink)) ? $this->Filehandle->readFilecontent($strTemplateFileLink) : "";
                    $strEbookContentDirty = $htmlChapter . $strEbookContentDirty;

                    // 3.1 replace translation-part
                    if (sizeof($arrFilename) > 1) {
                        $strFilename = explode(".tpl", $arrFilename[sizeof($arrFilename) - 1])[0];
                        if (file_exists("public/template/lang/" . $this->strAlpha2 . "/" . $strFilename . ".json")) {
                            $jsonLangMarker = $this->Filehandle->readFilecontent("public/template/lang/" . $this->strAlpha2 . "/" . $strFilename . ".json");
                            $arrLangMarker = json_decode($this->Filehandle->cleanJsonStr($jsonLangMarker), true);

                            foreach ($arrLangMarker[$strFilename] as $strLangMarker => $langtext) {
                                if (strpos($strEbookContentDirty, "{L:" . strtoupper($strLangMarker) . "}")) {
                                    $strEbookContentDirty = $this->MandN->setBlock($strEbookContentDirty, "L:" . strtoupper($strLangMarker), $langtext);
                                }
                            }
                        }
                    }

                    // 3.2 search, replace and copy images
                    if (strstr($strEbookContentDirty, '<img')) {
                        $arrImageParts = explode("src=\"", $strEbookContentDirty);
                        foreach ($arrImageParts as $key => $strImagePart) {
                            if (strstr($strImagePart, '.jpg') || strstr($strImagePart, '.png') || strstr($strImagePart, '.gif') || strstr($strImagePart, '.jpeg')) {
                                $arrImageLinks[] = explode("\"", $strImagePart)[0];
                            }
                        }

                        foreach ($arrImageLinks as $key => $strSourceLink) {
                            $arrImageName = explode("/", $strSourceLink);
                            $strImageName = $arrImageName[sizeof($arrImageName) - 1];
                            $strEbookContentDirty = str_replace($strSourceLink, "image/" . $strImageName, $strEbookContentDirty);

                            copy(substr($strSourceLink, 1), "public/extensions/epubbi/tmp/OEBPS/image/" . $strImageName);
                        }
                    }

                    $strEbookContent .= $strEbookContentDirty;
                }

                $strTmpEbook = $strEbookScaffoldContent;
                $strTmpEbook = $this->MandN->setBlock($strTmpEbook, "EPUBBI_CONTENT", $strEbookContent);
                $strTmpEbook = $this->MandN->setBlock($strTmpEbook, "EPUBBI_MANIFESTID", "content");
                $this->Filehandle->writeFilecontent($filelink, $strTmpEbook);
            }

            // 5.) titlepage and introduction.xhtml
            $arrEbookSites[] = array(
                "site" => "titlepage",
                "templatelink" => "public/extensions/epubbi/ebooktpl/titlepage.tpl",

            );
            $arrEbookSites[] = array(
                "site" => "introduction",
                "templatelink" => "public/extensions/epubbi/ebooktpl/lang/" . $this->strAlpha2 . "/introduction.tpl"
            );

            foreach ($arrEbookSites as $key => $page) {
                $filelink = "public/extensions/epubbi/tmp/OEBPS/" . $arrEbookSites[$key]['site'] . ".xhtml";
                if (file_exists($filelink)) {
                    $strEbookContent = (file_exists($arrEbookSites[$key]['templatelink'])) ? $this->Filehandle->readFilecontent($arrEbookSites[$key]['templatelink']) : "";
                    $strTmpEbook = $strEbookScaffoldContent;
                    $strTmpEbook = $this->MandN->setBlock($strTmpEbook, "EPUBBI_CONTENT", $strEbookContent);
                    $strTmpEbook = $this->MandN->setBlock($strTmpEbook, "EPUBBI_MANIFESTID", $arrEbookSites[$key]['site']);
                    $this->Filehandle->writeFilecontent($filelink, $strTmpEbook);
                }
            }
        }
    }

    public function copyEbookscaffold() {
        $this->Filehandle->cleanupDirectory("public/extensions/epubbi/tmp");
        $this->Filehandle->rcopy("public/extensions/epubbi/ebookscaffold/META-INF", "public/extensions/epubbi/tmp/META-INF");
        $this->Filehandle->rcopy("public/extensions/epubbi/ebookscaffold/OEBPS", "public/extensions/epubbi/tmp/OEBPS");
        copy("public/extensions/epubbi/ebookscaffold/mimetype", "public/extensions/epubbi/tmp/mimetype");
    }
}