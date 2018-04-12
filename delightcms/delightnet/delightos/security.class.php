<?php
/*
 * security methods, for example strip tags of strings
 * 
 * @author    Gunnar von Spreckelsen <service@ihrwebentwickler.de>
 * @package   delightos
 */

namespace delightnet\delightos;

class Security {
    public $whitelist;
    public $cypher;
    public $mode;
    public $salt;

    public $Filehandle;

    public function __construct() {
        $this->Filehandle = new Filehandle();
        $this->whitelist = "a-zA-Z0-9äöüÄÖÜ§\+\-\!\*\#@ß";
        $this->cypher = "rijndael_256";
        $this->mode = "ecb";

        $arrSalt = (file_exists("public/configuration/salt.json")) ? json_decode($this->Filehandle->readFilecontent("public/configuration/salt.json"), true) : null;
        $this->salt = $arrSalt[0];
    }

    /**
     * decode string (mode by $this->mode)
     *
     * @param string $strToDecode
     * @return string $crypted
     */
    public function decodeString($strToDecode) {
        $mcrypt = mcrypt_module_open($this->cypher, "", $this->mode, "");
        $iv = @mcrypt_create_iv(mcrypt_enc_get_iv_size($mcrypt), MCRYPT_RAND);
        @mcrypt_generic_init($mcrypt, $this->salt . session_id(), $iv);
        $crypted = (string)mcrypt_generic($mcrypt, $strToDecode);
        @mcrypt_generic_deinit($mcrypt);

        return $crypted;
    }

    /**
     * encode string (mode by $this->mode)
     *
     * @param string $strEncoded
     * @return string $decrypted
     */
    public function encodeString($strEncoded) {
        $mcrypt = mcrypt_module_open($this->cypher, "", $this->mode, "");
        $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($mcrypt), MCRYPT_RAND);
        @mcrypt_generic_init($mcrypt, $this->salt . session_id(), $iv);
        $decrypted = (string)@mdecrypt_generic($mcrypt, $strEncoded);
        @mcrypt_generic_deinit($mcrypt);

        return $decrypted;
    }

    /**
     * undo magic quotes
     *
     * @param string || array $strOrArray)
     * @return array $newArray
     */
    public function undoMagicQuotes($strOrArray) {
        if (is_string($strOrArray)) {
            return stripslashes($strOrArray);
        } else {
            $newArray = array();

            foreach ($strOrArray as $key => $value) {
                if (is_array($value)) {
                    $newArray[$key] = $this->undoMagicQuotes($value, false);
                } else {
                    $newArray[$key] = stripslashes($value);
                }
            }
            return $newArray;
        }
    }

    /**
     * undo html-tags from string
     *
     * @param string || array $strOrArray)
     * @return array $newArray
     */
    public function undoTags($strOrArray) {
        if (is_string($strOrArray)) {
            return strip_tags($strOrArray);
        } else {
            $newArray = array();

            foreach ($strOrArray as $key => $value) {
                if (is_array($value)) {
                    $newArray[$key] = $this->undoTags($value, false);
                } else {
                    $newArray[$key] = strip_tags($value);
                }
            }
            return $newArray;
        }
    }

    /**
     * check string of whitelist-content
     *
     * @param string $strLiteral
     * @return bool $isWhiteList
     */
    public function checkOfWhitelist($strLiteral) {
        $isWhiteList = true;

        if (!preg_match("#^[a-zA-Z0-9" . $this->whitelist . "]+$#", $strLiteral)) {
            $isWhiteList = false;
        }

        return $isWhiteList;
    }
}