<?php
/*
 * security methods, for example strip tags of strings
 * 
 * @author    Gunnar von Spreckelsen <service@ihrwebentwickler.de>
 * @package   delightos
 */

namespace delightnet\delightos;

class Security {
    public string $whitelist;
    public Filehandle $Filehandle;

    public function __construct() {
        $this->Filehandle = new Filehandle();
        $this->whitelist = "a-zA-Z0-9äöüÄÖÜ§\+\-\!\*\#@ß";
    }

    /**
     * decode string
     *
     * @param string $strToDecode
     * @return string
     */
    public function decodeString(string $strToDecode): string {
        return base64_decode($strToDecode);
    }

    /**
     * encode string
     *
     * @param string $strEncoded
     * @return string
     */
    public function encodeString(string $strEncoded): string {
        return base64_encode($strEncoded);
    }

    /**
     * undo magic quotes
     *
     * @param $strInput string
     * @return string
     */
    public function undoMagicQuotes(string $strInput): string {
        if (is_string($strInput)) {
            return stripslashes($strInput);
        }

        return "";
    }

    /**
     * undo html-tags from strings
     * @param array $htmlTags
     * @return array
     */
    public function undoTags(array $htmlTags): array {
        $newArray = array();

        foreach ($htmlTags as $key => $value) {
            if (is_array($value)) {
                $newArray[$key] = $this->undoTags($value);
            } else {
                $newArray[$key] = strip_tags($value);
            }
        }
        return $newArray;
    }

    /**
     * check string of whitelist-content
     *
     * @param string $strLiteral
     * @return bool $isWhiteList
     */
    public function checkOfWhitelist(string $strLiteral): bool {
        $isWhiteList = true;

        if (!preg_match("#^[a-zA-Z0-9" . $this->whitelist . "]+$#", $strLiteral)) {
            $isWhiteList = false;
        }

        return $isWhiteList;
    }
}