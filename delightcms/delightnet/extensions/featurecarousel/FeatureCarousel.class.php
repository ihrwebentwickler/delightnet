<?php

/*
 * FeatureCarousel, input and output of Carousel-data,
 * plugin-site: http://www.bkosborne.com/jquery-feature-carousel (Author: Brian Osborne, brian@bkosborne.com)
 * 
 * @author    Gunnar von Spreckelsen <service@ihrwebentwickler.de>
 * @package   delightos
 * @version   1.10
 * 
 */

namespace delightnet\extensions\FeatureCarousel;

use delightnet\delightos\Registry;
use delightnet\delightos\Request;
use \DirectoryIterator;

class FeatureCarousel {

    public $pluginNb;
    public $Mandn;
    public $Filehandle;
    public $objectCarousel;
    public $carouselTemplate;
    public $partCarousel;
    public $partImageDescription;

    public function __construct($configuration) {
        $this->objectCarousel = $configuration;
        $this->carouselTemplate = Registry::get('Filehandle')->readFilecontent("public/extensions/FeatureCarousel/template/FeatureCarousel.tpl");
        $this->partCarousel = Registry::get('Filehandle')->readFilecontent("public/extensions/FeatureCarousel/template/parts/featureCarousel.tpl");
        $this->partImageDescription = Registry::get('Filehandle')->readFilecontent("public/extensions/FeatureCarousel/template/parts/partImageDescription.tpl");
    }

    public function setStandardMarker($pluginnb) {
        $strCarouselTemplate = $this->carouselTemplate;
        $strCarouselTemplate = Registry::get('Mandn')->setBlock($strCarouselTemplate, "FETCAR_NB", $pluginnb);

        $this->objectCarousel->FeatureCarousel->FeatureCarousel->$pluginnb->carouselSpeed = 
                (isset($this->objectCarousel->FeatureCarousel->FeatureCarousel->$pluginnb->carouselSpeed)) ?
                $this->objectCarousel->FeatureCarousel->FeatureCarousel->$pluginnb->carouselSpeed : "";

        $strCarouselTemplate = Registry::get('Mandn')->setBlock($strCarouselTemplate, "CAROUSELSPEED", $this->objectCarousel->FeatureCarousel->FeatureCarousel->$pluginnb->carouselSpeed);

        return $strCarouselTemplate;
    }

    public function startFeatureCarousel($pluginnb) {
        $strGalery = '';
        (string) $pluginnb;
        if (isset($this->objectCarousel->FeatureCarousel->FeatureCarousel->$pluginnb->folder)
                && isset($this->objectCarousel->FeatureCarousel->FeatureCarousel->$pluginnb->carouselSpeed)
                && sizeof($this->objectCarousel->FeatureCarousel->FeatureCarousel->$pluginnb) > 0
        ) {
            foreach ($this->objectCarousel->FeatureCarousel->imageProperties->$pluginnb as $imageKey => $imageName) {
                if (file_exists("public/extensions/FeatureCarousel/images/"
                        . $this->objectCarousel->FeatureCarousel->FeatureCarousel->$pluginnb->folder
                        . "/"
                        . $this->objectCarousel->FeatureCarousel->imageProperties->$pluginnb->$imageKey->imageName)
                ) {
                    $strCarouselPart = $this->partCarousel;
                    $strGalery .= Registry::get('Mandn')->setBlock($strCarouselPart, "FEATCAR_IMAGELINK", $this->objectCarousel->FeatureCarousel->FeatureCarousel->$pluginnb->folder
                            . "/" . $this->objectCarousel->FeatureCarousel->imageProperties->$pluginnb->$imageKey->imageName);

                    if (isset($this->objectCarousel->FeatureCarousel->imageProperties->$pluginnb->$imageKey->description)) {
                        $strGalery = Registry::get('Mandn')->setBlock($strGalery, "FETCAR_DESCRIPTION", $this->partImageDescription);
                        $strGalery = Registry::get('Mandn')->setBlock($strGalery, "FETCAR_DESCRIPTION_CONTENT", $this->objectCarousel->FeatureCarousel->imageProperties->$pluginnb->$imageKey->description);
                    } else {
                        $strGalery = Registry::get('Mandn')->setBlock($strGalery, "FETCAR_DESCRIPTION", "");
                    }
                }
            }
        }

        $strCarouselTemplate = $this->setStandardMarker($pluginnb);
        $strCarouselTemplate = Registry::get('Mandn')->setBlock($strCarouselTemplate, "FETCAR_IMAGE", $strGalery);

        return $strCarouselTemplate;
    }

}