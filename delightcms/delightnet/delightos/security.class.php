<?php
/*
 * security-methods
 * 
 * @author    Gunnar von Spreckelsen <service@ihrwebentwickler.de>
 * @package   delightos
 */

namespace delightnet\delightos;

class Security {
    const SESS_CIPHER = 'aes-128-cbc';

    public $salt;
    public $whitelist;

    public $Filehandle;

    public function __construct() {
        $this->salt = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/delightnet/delightos/salt.php", true);
        $this->whitelist = "a-zA-Z0-9äöüÄÖÜ§\+\-\!\*\#@ß";
        $this->Filehandle = new Filehandle();
    }

    /**
     * decode string
     *
     * @param string $strToDecode
     * @return string $crypted
     */
    public function decodeString($strToDecode) {
        $c = base64_decode($strToDecode);
        $ivlen = openssl_cipher_iv_length($cipher = self::SESS_CIPHER);
        $iv = substr($c, 0, $ivlen);
        $ciphertext_raw = substr($c, $ivlen/*+$sha2len*/);
        $crypted = openssl_decrypt($ciphertext_raw, $cipher, $this->salt, $options = OPENSSL_RAW_DATA, $iv);

        return $crypted;
    }

    /**
     * encode string
     *
     * @param string $strEncoded
     * @return string $decrypted
     */
    public function encodeString($strEncoded) {
        $ivlen = openssl_cipher_iv_length($cipher = self::SESS_CIPHER);
        $iv = openssl_random_pseudo_bytes($ivlen);
        $ciphertext_raw = openssl_encrypt($strEncoded, $cipher, $this->salt, $options = OPENSSL_RAW_DATA, $iv);
        $decrypted = base64_encode($iv ./*$hmac.*/
            $ciphertext_raw);
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
                    $newArray[$key] = $this->undoMagicQuotes($value);
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
                    $newArray[$key] = $this->undoTags($value);
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