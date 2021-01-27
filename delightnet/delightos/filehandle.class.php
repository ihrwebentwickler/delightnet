<?php

/*
 * filehandle-operations including recursiv directory reading
 * 
 * @author    Gunnar von Spreckelsen <service@ihrwebentwickler.de>
 * @package   delightos
 * 
 */

namespace delightnet\delightos;

use DirectoryIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;
use \ZipArchive;

class Filehandle {

    /**
     * read and get content of file
     *
     * @param string $fileLink
     * @return string $fileContent | boolean
     */
    public function readFilecontent(string $fileLink): string {
        $fileinfo = new SplFileInfo($fileLink);

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
     * @return void
     */
    public function writeFilecontent(string $fileLink, string $fileContent): void {
        if (strlen($fileContent) > 0 && file_exists($fileLink)) {
            $filehandle = fopen($fileLink, 'w');
            fwrite($filehandle, stripslashes($fileContent), strlen($fileContent));
            fclose($filehandle);
        }
    }

    /**
     * get all dirnames non-recursive
     *
     * @param string $dir
     * @return array $arrayListDir
     */
    public function readSubfolderNamesNonRecursive(string $dir): array {
        $arrayListDir = array();

        if ($handler = opendir($dir)) {
            while (($sub = readdir($handler)) !== FALSE) {
                if ($sub != "." && $sub != ".." && $sub != "Thumb.db" && $sub != "Thumbs.db") {
                    if (is_dir($dir . "/" . $sub)) {
                        $arrayListDir[$sub] = Filehandle::readSubfolderNamesNonRecursive($dir . "/" . $sub);
                    }
                }
            }
            closedir($handler);
        }

        return $arrayListDir;
    }

    /**
     * read Filenames from Directory (non-recursive), by true with complete directory-string
     *
     * @param string $directory
     * @param bool $hasPathName
     * @param array $arrayDeniedFile
     * @return array $arrayFiles
     */
    public function readDirectoryNonRecursive(
        string $directory = "",
        bool $hasPathName = true,
        array $arrayDeniedFile = array()
    ): array {
        $iterator = new DirectoryIterator ($directory);
        $arrayFiles = null;

        foreach ($iterator as $info) {
            if ($info->isFile() && !$info->isDot()) {
                if (!in_array($info->getExtension(), $arrayDeniedFile)) {
                    $strFilelink = ($hasPathName === true) ?
                        $info->getPath() . "/" . $info->getBasename() : $info->getBasename();
                    $arrayFiles[] = $strFilelink;
                }
            }
        }

        if ($arrayFiles !== null) {
            sort($arrayFiles);
        }

        return $arrayFiles;
    }

    /**
     * create a zip-archiv from various sources
     *
     * @param $archivFileName
     * @param array / string $sources
     * @return ZipArchive | boolean
     */
    public function createZipArchiv(string $archivFileName, array $sources) {
        if (!extension_loaded('zip')) {
            return false;
        }

        if (is_array($sources)) {
            $arraySources = $sources;
        } else {
            $arraySources[0] = $sources;
        }

        $zip = new ZipArchive();
        if (!$zip->open('tmp/' . $archivFileName, ZIPARCHIVE::CREATE)) {
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
            } else if (is_file($source) === true && isset($file) && filesize($file) > 0) {
                $zip->addFromString(basename($source), file_get_contents($source));
            }
        }

        return $zip->close();
    }

    /**
     * send zip-archiv to header and unlink temp-zip-archiv,
     * the content-type of the header is -application/octet-stream-
     *
     * @param string $archivFileName
     * @param string $sources
     * @return void
     */
    public function getZipArchiv(string $archivFileName, string $sources): void {
        if (is_array($sources)) {
            $arraySources = $sources;
        } else {
            $arraySources[0] = $sources;
        }

        $zip = $this->createZipArchiv($archivFileName, $arraySources);

        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="' . $archivFileName . '"');
        header('Content-Length: ' . filesize('tmp/' . $archivFileName));
        readfile('tmp/' . $archivFileName);
        @unlink('tmp/' . $archivFileName);
        unset($zip);
    }

    /**
     * write a simple ini-file from array
     *
     * @param string $fileLink
     * @param array $arrayIniData
     * @return void
     */
    public function writeSimpleIniFile(string $fileLink, array $arrayIniData): void {
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
     * @return string
     */
    public function cleanIniStr(string $strIni): string {
        $strIni = preg_replace('#"#', "'", $strIni);
        return $strIni;
    }

    /**
     * clean json-string: delete escape-sequences like breaks and
     * other chars
     *
     * @param string $strJason
     * @return string
     */
    public function cleanJsonStr(string $strJason): string {
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

    /**
     * Recursively copy folders/ files from one directory to another
     *
     * @param String $src
     * @param String $dest
     * @return bool
     */
    public function rcopy(string $src, string $dest): bool {
        if (!is_dir($src)) {
            return false;
        }

        if (!is_dir($dest)) {
            if (!mkdir($dest)) {
                return false;
            }
        }

        $i = new DirectoryIterator($src);
        foreach ($i as $f) {
            if ($f->isFile()) {
                copy($f->getRealPath(), "$dest/" . $f->getFilename());
                chmod("$dest/" . $f->getFilename(), 0777);
            } else if (!$f->isDot() && $f->isDir()) {
                $this->rcopy($f->getRealPath(), "$dest/$f");
            }
        }

        @chmod($dest, 0777);
        return true;
    }

    /**
     * Recursively clean/ delete folder-contens
     *
     * @param String $dir
     * @return void
     */
    public function cleanupDirectory(string $dir): void {
        foreach (new DirectoryIterator($dir) as $file) {
            if ($file->isDir()) {
                if (!$file->isDot()) {
                    $this->cleanupDirectory($file->getPathname());
                }
            } else {
                unlink($file->getPathname());
            }
        }
    }
}