<?php

/*
 * MediaDirectPlayer, input and output of HTML5-Media-Player-Envirement
 * 
 * @author    Gunnar von Spreckelsen <service@ihrwebentwickler.de>
 * @package   delightos
 * @version   1.10
 * 
 */

namespace delightnet\extensions\MediaDirectPlayer;

use delightnet\delightos\Registry;
use delightnet\delightos\Request;

class MediaDirectPlayer {

    public $objMediaPlayer;
    public $mediaPlayerTemplate;
    public $partMediaPlayer;

    public function __construct($configuration) {
        $this->objMediaPlayer = $configuration;
        $this->mediaPlayerTemplate = Registry::get("Filehandle")->readFilecontent("public/extensions/MediaDirectPlayer/template/MediaDirectPlayer.tpl");
        $this->partMediaPlayer = Registry::get("Filehandle")->readFilecontent("public/extensions/MediaDirectPlayer/template/parts/MediaDirectPlayer.tpl");
    }

    public function buildJsAndDomData($pluginnb) {
        $strTemplate = $this->mediaPlayerTemplate;
        (string) $pluginnb;

        if (sizeof($this->objMediaPlayer->MediaDirectPlayer->MediaDirectPlayer->$pluginnb) > 0) {
            foreach ($this->objMediaPlayer->MediaDirectPlayer->MediaDirectPlayer->$pluginnb as $instanceKey => $instanceValue) {
                $strTemplate = Registry::get("Mandn")->setBlock($strTemplate, "MEDIADIRECTPLAYER_" . strtoupper($instanceKey), $instanceValue);
            }
        } else {
            $strTemplate = Registry::get("Mandn")->setBlock($strTemplate, "MEDIADIRECTPLAYER_MEDIAWIDTH", "");
            $strTemplate = Registry::get("Mandn")->setBlock($strTemplate, "MEDIADIRECTPLAYER_MEDIAHEIGHT", "");
            $strTemplate = Registry::get("Mandn")->setBlock($strTemplate, "MEDIADIRECTPLAYER_IMAGEFOLDER", "");
            $strTemplate = Registry::get("Mandn")->setBlock($strTemplate, "MEDIADIRECTPLAYER_AUTOSTART", 0);
            $strTemplate = Registry::get("Mandn")->setBlock($strTemplate, "MEDIADIRECTPLAYER_WELCOMEIMAGE", 0);
        }

        $strSliderImagesList = "";
        $strJsVideoArray = "{\n";
        $counterMedia = 1;
        $lengthObjMedia = (sizeof(get_object_vars($this->objMediaPlayer->MediaDirectPlayer->galery->$pluginnb)));
        if (sizeof($this->objMediaPlayer->MediaDirectPlayer->galery->$pluginnb) > 0) {
            foreach ($this->objMediaPlayer->MediaDirectPlayer->galery->$pluginnb as $key => $objMedia) {
                $strJsVideoArray .= "\t\tmedia" . $key . ": {\n";
                $strJsVideoArray .= "\t\t\tmediaTitle: \"" . $this->objMediaPlayer->MediaDirectPlayer->galery->$pluginnb->$key->mediaTitle . "\",\n";
                $strJsVideoArray .= "\t\t\tmediaPoster: \"" . $this->objMediaPlayer->MediaDirectPlayer->galery->$pluginnb->$key->mediaPoster . "\",\n";
                $strJsVideoArray .= "\t\t\tmediaDescription: \"" . $this->objMediaPlayer->MediaDirectPlayer->galery->$pluginnb->$key->mediaDescription . "\",\n";
                $strJsVideoArray .= "\t\t\tmediaLink1: \"" . $this->objMediaPlayer->MediaDirectPlayer->galery->$pluginnb->$key->mediaLink1 . "\",\n";
                $strJsVideoArray .= "\t\t\tmediaLink2: \"" . $this->objMediaPlayer->MediaDirectPlayer->galery->$pluginnb->$key->mediaLink2 . "\"\n";
                $strJsVideoArray .= ($lengthObjMedia != $counterMedia) ? "\t\t},\n" : "\t\t}\n";

                $strSliderImagesList .= "\t\t" . $this->partMediaPlayer;
                $strSliderImagesList .= (count($this->objMediaPlayer->MediaDirectPlayer->galery->$pluginnb) == $key) ? "" : "\n";
                $strSliderImagesList = Registry::get("Mandn")->setBlock($strSliderImagesList, "MEDIADIRECTPLAYER_GALERYIMAGE", $this->objMediaPlayer->MediaDirectPlayer->galery->$pluginnb->$key->galeryImage);
                $strSliderImagesList = Registry::get("Mandn")->setBlock($strSliderImagesList, "MEDIADIRECTPLAYER_MEDIATITLE", $this->objMediaPlayer->MediaDirectPlayer->galery->$pluginnb->$key->mediaTitle);
                $strSliderImagesList = Registry::get("Mandn")->setBlock($strSliderImagesList, "MEDIADIRECTPLAYER_MEDIANB", $key);
                
                $counterMedia++;
            }

            $strJsVideoArray .= "\t}";
        }

        $strTemplate = Registry::get("Mandn")->setBlock($strTemplate, "JS_OBJMEDIA", $strJsVideoArray);
        $strTemplate = Registry::get("Mandn")->setBlock($strTemplate, "MEDIADIRECTPLAYER_IMAGES", $strSliderImagesList);
        $strTemplate = Registry::get("Mandn")->setBlock($strTemplate, "INSTANCEID", $pluginnb);

        return $strTemplate;
    }

    function startMediaDirectPlayer($pluginnb, Request $request) {
        if ($this->objMediaPlayer == null) {
            return "";
        }

        $strTemplate = $this->buildJsAndDomData($pluginnb);
        return $strTemplate;
    }

}