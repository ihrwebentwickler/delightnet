<?php

/*
 * filehandle-operations including recursiv directory reading
 * 
 * @author    Gunnar von Spreckelsen <service@ihrwebentwickler.de>
 * @package   delightos
 * @version   4.20
 * 
 */

namespace delightnet\delightos;

use \DirectoryIterator;
use \RecursiveDirectoryIterator;
use \RecursiveIteratorIterator;
use \ZipArchive;

class Filehandle {

    /**
     * read and get content of file
     *  
     * @param string $fileLink
     * @return string $fileContent
     */
    public function readFilecontent($fileLink) {
        $fileinfo = new \SplFileInfo($fileLink);

        if ($fileinfo->isReadable()) {
            $handle = @fopen($fileLink, "r");
            $fileContent = fread($handle, filesize($fileLink));
            fclose($handle);

            return $fileContent;
        } else {
            return false;
        }
    }

    /**
     * write content of file, version without chmod
     *  
     * @param string $fileLink
     * @param string $fileContent
     */
    public function writeFilecontent($fileLink, $fileContent) {
        if (strlen($fileContent) > 0 && file_exists($fileLink)) {
            $fileContent = stripslashes($fileContent);
            $filehandle = fopen($fileLink, 'w');
            fwrite($filehandle, $fileContent, strlen($fileContent));
            fclose($filehandle);
        }
    }

    /**
     * write content of file, version without chmod, the result is
     * sorted in alphabetical order
     *  
     * @param object DirectoryIterator $iterator
     * @return array $arrayFilenames
     */
    public function readDirectoryNonRecursive(DirectoryIterator $iterator) {
        $arrayFilenames = array();
        $intarrayKey = 0;

        foreach ($iterator as $fileinfo) {
            if (!$fileinfo->isDir() && !$fileinfo->isDot() && $fileinfo->isFile()) {
                $arrayFilenames[$intarrayKey] = $fileinfo->getFilename();
                $intarrayKey++;
            }
        }

        sort($arrayFilenames);
        return $arrayFilenames;
    }

    /**
     * create a zip-archiv from various sources
     *  
     * @param $archivFileName
     * @param array/ string $sources
     * @return object $zip
     */
    public function createZipArchiv($archivFileName, $sources) {
        if (is_array($sources)) {
            $arraySources = $sources;
        } else {
            $arraySources[0] = $sources;
        }

        if (!extension_loaded('zip')) {
            return false;
        }

        $zip = new ZipArchive();
        if (!$zip->open($archivFileName, ZIPARCHIVE::CREATE)) {
            return false;
        }

        foreach ($arraySources as $key => $source) {
            if (!file_exists($source)) {
                continue;
            }

            $source = str_replace('\\', '/', realpath($source));

            if (is_dir($source) === true) {
                $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source));
                foreach ($files as $file) {
                    $file = str_replace('\\', '/', realpath($file));

                    if (is_dir($file) === true && filesize($file) > 0) {
                        $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
                    } else if (is_file($file) === true && filesize($file) > 0) {
                        $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
                    }
                }
            } else if (is_file($source) === true && filesize($file) > 0) {
                $zip->addFromString(basename($source), file_get_contents($source));
            }
        }

        return $zip->close();
    }

    /**
     * send zip-archiv to header and unlink temp-zip-archiv,
     * the content-type of the header is -application/octet-stream-
     *  
     * @param $archivFileName
     * @param array $sources
     */
    public function getZipArchiv($archivFileName, $sources) {
        $zip = $this->createZipArchiv($archivFileName, $sources);

        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=" . $archivFileName);
        readfile($archivFileName);

        @unlink($archivFileName);
        unset($zip);
    }

    /**
     * transform a ini-file to a mutiple array
     *  
     * @param string $fileLink
     * @return array $arrayIniTransformed
     */
    public function transformIniFileToMutipleArray($filelink) {
        $arrayIni = parse_ini_file($filelink, TRUE);
        $arraySplitIni = array();
        $arrayIniTransformed = array();

        foreach ($arrayIni as $groupKey => $groupArray) {
            foreach ($groupArray as $contentKey => $strContent) {
                $arraySplitIni = explode(".", $contentKey);
                switch (sizeof($arraySplitIni)) {
                    case 0:
                        $arrayIniTransformed = null;
                        break;
                    case 1:
                        $arrayIniTransformed[$groupKey][$arraySplitIni[0]] = $strContent;
                        break;
                    case 2:
                        $arrayIniTransformed[$groupKey][$arraySplitIni[0]][$arraySplitIni[1]] = $strContent;
                        break;
                    case 3:
                        $arrayIniTransformed[$groupKey][$arraySplitIni[0]][$arraySplitIni[1]][$arraySplitIni[2]] = $strContent;
                        break;
                    case 4:
                        $arrayIniTransformed[$groupKey][$arraySplitIni[0]][$arraySplitIni[1]][$arraySplitIni[2]][$arraySplitIni[3]] = $strContent;
                        break;
                    default:
                        $arrayIniTransformed = null;
                }
            }
        }

        return $arrayIniTransformed;
    }

    /**
     * write a simple ini-file from array
     *  
     * @param string $fileLink
     * @param array $arrayIniData
     */
    public function writeSimpleIniFile($fileLink, array $arrayIniData) {
        $strIniFile = '';
        $intNbContent = 0;
        $intNb = 1;
        $isFirstHeaderKey = true;

        foreach ($arrayIniData as $IniHeaderKey => $IniHeaderArray) {
            if ($isFirstHeaderKey === true) {
                $isFirstHeaderKey = false;
            } else {
                $strIniFile .= "\n\n";
            }

            $strIniFile .= '[' . $IniHeaderKey . ']';
            $strIniFile .= "\n";

            foreach ($IniHeaderArray as $contentKey => $contentValue) {
                $quotContentValue = (gettype($contentValue) == "string") ? '"' : '';
                $strIniFile .= $contentKey . ' = ' . $quotContentValue . $contentValue . $quotContentValue;

                if ($intNbContent === 0) {
                    $intNbContent = count($IniHeaderArray);
                }

                if ($intNb != $intNbContent) {
                    $strIniFile .= "\n";
                }

                $intNb++;
            }
        }

        chmod($fileLink, 0777);
        $this->writeFilecontent($fileLink, $strIniFile);
        chmod($fileLink, 0644);
    }

    /**
     * clean an ini-string: double-quoted to simple-quotes
     *  
     * @param string $strIni
     * @return string $strIni
     */
    public function cleanIniStr($strIni) {
        $strIni = preg_replace('#"#', "'", $strIni);
        return $strIni;
    }

    /**
     * clean json-string: delete escape-sequences like breaks and
     * repair JSON-UTF8-Bug (better solution will follow soon)
     *  
     * @param string $strJason
     * @return string $strJason
     */
    public function cleanJsonStr($strJason) {
        $strJason = preg_replace('#(\r\n|\n|\r)#', '', $strJason);
        
        $strJason = preg_replace('#(u00dc)#', 'Ü', $strJason);
        $strJason = preg_replace('#(u00fc)#', 'ü', $strJason);
        $strJason = preg_replace('#(u00d6)#', 'Ö', $strJason);
        $strJason = preg_replace('#(u00f6)#', 'ö', $strJason);
        $strJason = preg_replace('#(u00c4)#', 'Ä', $strJason);
        $strJason = preg_replace('#(u00e4)#', 'ä', $strJason);
        $strJason = preg_replace('#(u00df)#', 'ß', $strJason);
        
        return $strJason;
    }

    /*
     * not working, next implementation, see function cleanJsonStr here!!
     * 
     * Rebuild Array with unencoded JSON
     * to full UTF8-representation 
     *  
     * @param array $arrayJson
     * @return array $newJsonArray
     */
    public static function rebuildArrayToFullJsonUtfRepresentation($arrayJson) {
        if (is_string($arrayJson)) {
            return utf8_decode($arrayJson); 
        } else {
            $newJsonArray = array();

            foreach ($arrayJson as $key => $value) {
                if (is_array($value)) {
                    $newJsonArray[$key] = Filehandle::rebuildArrayToFullJsonUtfRepresentation($value, false);
                } else {
                    $newJsonArray[$key] = utf8_decode($value);
                }
            }
            return $newJsonArray;
        }
    }

}