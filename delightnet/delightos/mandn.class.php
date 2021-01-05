<?php

/*
 * very small own template-language, a bit like smarty, but MandN tasts good to
 * 
 * @author    Gunnar von Spreckelsen <service@ihrwebentwickler.de>
 * @package   delightos
 * 
 */

namespace delightnet\delightos;

class MandN {
    public ?string $strCssLink;
    public ?string $strJsLink;
    public array $arrayIntegrationFileExtensions;
    public Filehandle $Filehandle;

    public function __construct(Filehandle $Filehandle, Request $objRequest) {
        $this->strCssLink = (file_exists($objRequest->getDocumentRoot() . "/shared/public/template/parts/include_css.tpl")) ?
            $Filehandle->readFilecontent($objRequest->getDocumentRoot() . "/shared/public/template/parts/include_css.tpl") : null;

        $this->strJsLink = (file_exists($objRequest->getDocumentRoot() . "/shared/public/template/parts/include_js.tpl")) ?
            $Filehandle->readFilecontent($objRequest->getDocumentRoot() . "/shared/public/template/parts/include_js.tpl") : null;

        $this->arrayIntegrationFileExtensions = array(".css", ".js");
        $this->Filehandle = $Filehandle;
    }

    /**
     * set HTML-Marker in Template
     * @param string $strContent
     * @param string $blockname
     * @param string $strValue
     * @return string $strContent
     */
    public function setBlock(string $strContent, string $blockname, string $strValue): string {
        $strContent = str_replace("{" . strtoupper($blockname) . "}", $strValue, $strContent);
        return $strContent;
    }

    /**
     * delete Control-characters (r, n, t) and whitespace from string
     *
     * @param string $strText
     * @return string $strText
     */
    public function deleteControlCharactersAndWhitespaceFromString(string $strText): string {
        $strText = preg_replace('~[\r\n\t]+~', '', $strText);
        return $strText;
    }

    public function replaceLoops(object $objectData, string $template): string {
        $strCompleteReplaced = "";
        foreach ($objectData->loop as $key => $objectLoop) {
            $strRegex = '#{' . 'LOOP ' . strtoupper($objectLoop->key) . '}.*.{/LOOP}#isU';
            preg_match_all($strRegex, $template, $arrayContentLoop);

            if (isset($arrayContentLoop[0][0])) {
                $strPart = explode('{LOOP ' . strtoupper($objectLoop->key) . '}', $arrayContentLoop[0][0])[1];
                $strPart = trim(explode('{/LOOP}', $strPart)[0]);
            }

            $template = preg_replace($strRegex, "{" . strtoupper($objectLoop->key) . "}", $template);

            if (isset($strPart) && is_array($objectData->{$objectLoop->key})) {
                foreach ($objectData->{$objectLoop->key} as $keyData => $objData) {
                    $arrayKeyValue = get_object_vars($objData);
                    if (is_array($arrayKeyValue)) {
                        $strPartNew = $strPart;
                        foreach ($arrayKeyValue as $keyConverted => $valueConverted) {
                            $strPartNew = $this->setBlock($strPartNew, strtoupper($keyConverted), $valueConverted);
                        }

                        $strCompleteReplaced .= $strPartNew;
                    }
                }
            }
            $template = $this->setBlock($template, $objectLoop->key, $strCompleteReplaced);
        }

        return $template;
    }

    /**
     * replace loop-content of a string, replace in main-string and return main-string
     *
     * @param string $strContentInput
     * @param string $strLoopIdentifier
     * @param array $arrayLoopInput
     * @param string $strActionSearchValue
     * @param string $strAction
     * @param string $cmd
     * @param string $strSearchKey
     * @return string
     */
    public function replaceLoopContent(
        string $strContentInput,
        string $strLoopIdentifier,
        array $arrayLoopInput,
        string $strActionSearchValue,
        string $strAction,
        string $cmd,
        string $strSearchKey
    ): string {
        $strRegex = '#{' . 'LOOP ' . strtoupper($strLoopIdentifier) . '}.*.{/LOOP}#isU';
        $strContent = preg_replace($strRegex, '{' . strtoupper($strLoopIdentifier) . '}', $strContentInput);
        preg_match_all($strRegex, $strContentInput, $arrayContentLoop);

        if (isset($arrayContentLoop[0]) && isset($arrayContentLoop[0][0])) {
            $strContentLoop = explode('{LOOP ' . strtoupper($strLoopIdentifier) . '}', $arrayContentLoop[0][0])[1];
            $strContentLoop = trim(explode('{/LOOP}', $strContentLoop)[0]);

            $strContentPart = "";
            foreach ($arrayLoopInput as $keyEntry => $arrayEntry) {
                $strContentPart .= $strContentLoop;
                foreach ($arrayLoopInput[$keyEntry] as $keyBlock => $arrayBlock) {
                    $strContentPart = $this->setBlock($strContentPart, $keyBlock, $arrayLoopInput[$keyEntry][$keyBlock]);
                    if (isset($strActionSearchValue) && isset($strAction)) {
                        $strContentPart = ($arrayLoopInput[$keyEntry][strtoupper($strSearchKey)] === $cmd) ?
                            $this->setBlock($strContentPart, $strActionSearchValue, $strAction) :
                            $this->setBlock($strContentPart, $strActionSearchValue, '');
                    }
                }
            }

            $strContent = $this->setBlock($strContent, strtoupper($strLoopIdentifier), $strContentPart);
        }

        return $strContent;
    }


    /**
     * replace dynamics links in html-template (dynamic filelink-integration)
     *
     * @param string $template
     * @param $links
     * @return string $template
     */
    public function replaceDynamicLinks(string $template, $links): string {
        if (!is_array($links)) {
            $arrayLinks[0] = $links;
        } else {
            $arrayLinks = $links;
        }

        $strDynamicLinks = "";
        foreach ($arrayLinks as $templatekey => $files) {
            if (!is_array($files)) {
                $strDynamicLinks .= $this->buildDynamicLink($files);
            } else {
                foreach ($files as $filekey => $filename) {
                    $strDynamicLinks .= $this->buildDynamicLink($filename);
                }
            }
        }

        $strDynamicLinks .= "{DYNAMICFILEINTEGRATION}\n";
        $template = $this->setBlock($template, "DYNAMICFILEINTEGRATION", $strDynamicLinks);

        return $template;
    }

    /**
     * build dynamics links in html-template (css- oder js-file-integration)
     *
     * @param string $filename
     * @return string $strDynamicLink
     */
    public function buildDynamicLink(string $filename): string {
        $strDynamicLink = "";
        foreach ($this->arrayIntegrationFileExtensions as $key => $value) {
            if (strpos($filename, $value) !== false) {
                $filename = (strpos($filename, "public") !== false) ? "/" . $filename : $filename;
                if ($value == ".css") {
                    $strDynamicLink = $this->setBlock($this->strCssLink, "SRC_CSS", $filename);
                } else {
                    $strDynamicLink = $this->setBlock($this->strJsLink, "SRC_JS", $filename);
                }
            }
        }

        return $strDynamicLink;
    }

    /**
     * set a hex-color-value to the rgb-format
     *
     * @param string $strRgb
     * @return array
     */
    public function hex2rgb(string $strRgb): array {
        return array(
            hexdec(substr($strRgb, 0, 2)),
            hexdec(substr($strRgb, 2, 2)),
            hexdec(substr($strRgb, 4, 2)),
        );
    }
}