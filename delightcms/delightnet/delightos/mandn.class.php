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
    public $strCssLink;
    public $strJsLink;
    public $arrayIntegrationFileExtensions;

    public $Filehandle;

    public function __construct($Filehandle, Request $objRequest) {
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
    public function setBlock($strContent, $blockname, $strValue) {
        $strContent = str_replace("{" . strtoupper($blockname) . "}", $strValue, $strContent);
        return $strContent;
    }

    /**
     * delete Control-characters (r, n, t) and whitespace from string
     *
     * @param string $strText
     * @return string $strText
     */
    public function deleteControlCharactersAndWhitespaceFromString($strText) {
        $strText = preg_replace('~[\r\n\t]+~', '', $strText);
        return $strText;
    }

    /**
     * replace loop-content of a string, replace in main-string and return main-string
     *
     * @param string $strContentInput
     * @param string $strLoopIdentifier
     * @param array $arrayLoopInput
     * @param string $strActionSearchValue
     * @param string $strAction
     * @return string $strContent
     */
    public function replaceLoopContent($strContentInput, $strLoopIdentifier, $arrayLoopInput, $strActionSearchValue = "", $strAction = "", $cmd, $strSearchKey) {
        $strRegex = '#{' . 'LOOP ' . strtoupper($strLoopIdentifier) . '}.*.{/LOOP}#isU';
        $strContent = preg_replace($strRegex, '{' . strtoupper($strLoopIdentifier) . '}', $strContentInput);

        preg_match_all($strRegex, $strContentInput, $arrayContentLoop);
        $strContentLoop = explode('{LOOP ' . strtoupper($strLoopIdentifier) . '}', $arrayContentLoop[0][0])[1];
        $strContentLoop = trim(explode('{/LOOP}', $strContentLoop)[0]);

        $strContentPart = "";
        foreach ($arrayLoopInput as $keyEntry => $arrayEntry) {
            $strContentPart .= $strContentLoop;
            foreach ($arrayLoopInput[$keyEntry] as $keyBlock => $arrayBlock) {
                $strContentPart = $this->setBlock($strContentPart, $keyBlock, $arrayLoopInput[$keyEntry][$keyBlock]);
                if ($strActionSearchValue !== "" && $strAction !== "") {
                    $strContentPart = ($arrayLoopInput[$keyEntry][strtoupper($strSearchKey)] === $cmd) ?
                        $this->setBlock($strContentPart, $strActionSearchValue, $strAction) :
                        $this->setBlock($strContentPart, $strActionSearchValue, '');
                }
            }
        }

        $strContent = $this->setBlock($strContent, strtoupper($strLoopIdentifier), $strContentPart);
        return $strContent;
    }


    /**
     * replace dynamics links in html-template (dynamic filelink-integration)
     *
     * @param string $template
     * @param string $links
     * @return string $template
     */
    public function replaceDynamicLinks($template, $links) {
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
    public function buildDynamicLink($filename) {
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
echo $strDynamicLink;
        return $strDynamicLink;
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
}