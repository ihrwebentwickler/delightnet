<?php

/*
 * For Galleria, a js-galery, frontend input and output of image-data
 * 
 * @author    Gunnar von Spreckelsen <service@ihrwebentwickler.de>
 * @package   delightos
 * @version   5.30
 * 
 */

namespace delightnet\extensions\Galleria;

use delightnet\delightos\Registry;
use delightnet\delightos\Request;

class Galleria {

    public $Mandn;
    public $Filehandle;
    public $objGalleria;
    public $galleriaTemplate;

    public function __construct($configuration) {
        $this->objGalleria = $configuration;
        $this->galleriaTemplate = Registry::get("Filehandle")->readFilecontent("public/extensions/Galleria/template/Galleria.tpl");
        $this->partGalleria = Registry::get("Filehandle")->readFilecontent("public/extensions/Galleria/template/parts/galleria.tpl");
    }

    public function startGalleria($pluginnb) {
        $strGalleria = "";
        (string) $pluginnb;
        if (isset($this->objGalleria->Galleria->Galleria->$pluginnb->folder) && sizeof($this->objGalleria->Galleria->GalleriaProperties->$pluginnb) > 0) {
            foreach ($this->objGalleria->Galleria->GalleriaProperties->$pluginnb as $imageKey => $objImage) {
                if (
                        file_exists("public/extensions/Galleria/images/"
                                . $this->objGalleria->Galleria->Galleria->$pluginnb->folder
                                . "/"
                                . $this->objGalleria->Galleria->GalleriaProperties->$pluginnb->$imageKey->imageName)
                ) {
                    $strGalleriaPart = $this->partGalleria;
                    $strGalleria .= Registry::get("Mandn")->setBlock($strGalleriaPart, "GALLERIA_IMAGESRC", $this->objGalleria->Galleria->Galleria->$pluginnb->folder . "/"
                            . $this->objGalleria->Galleria->GalleriaProperties->$pluginnb->$imageKey->imageName);

                    if (isset($this->objGalleria->Galleria->GalleriaProperties->$pluginnb->$imageKey->imageName)) {
                        $strGalleria = Registry::get("Mandn")->setBlock($strGalleria, "GALLERIA_DESCRIPTION", $this->objGalleria->Galleria->GalleriaProperties->$pluginnb->$imageKey->imageName);
                    } else {
                        $strGalleria = Registry::get("Mandn")->setBlock($strGalleria, "GALLERIA_DESCRIPTION", "");
                    }

                    if (isset($this->objGalleria->Galleria->GalleriaProperties->$pluginnb->$imageKey->title)) {
                        $strGalleria = Registry::get("Mandn")->setBlock($strGalleria, "GALLERIA_TITLE", $this->objGalleria->Galleria->GalleriaProperties->$pluginnb->$imageKey->title);
                    } else {
                        $strGalleria = Registry::get("Mandn")->setBlock($strGalleria, "GALLERIA_TITLE", "");
                    }
                }
            }
        }

        $strGalleriaTemplate = $this->galleriaTemplate;
        $strGalleriaTemplate = Registry::get("Mandn")->setBlock($strGalleriaTemplate, "GALLERIA_NB", $pluginnb);
        $strGalleriaTemplate = Registry::get("Mandn")->setBlock($strGalleriaTemplate, "GALLERIA", $strGalleria);

        return $strGalleriaTemplate;
    }

}