<?php

/*
 * Basic-Class Controller of delightos-MVC
 *
 * @author    Gunnar von Spreckelsen <service@ihrwebentwickler.de>
 * @package   delightos
 *
 */

namespace delightnet\delightos;

abstract class Controller implements ControllerInterface {
    public $strExtName;
    public $strInstanceId;
    public $strExtTemplate;
    public $objConfiguration;
    public $arrLangs;
    public $strAlpha2;
    public $objRequest;

    public $Filehandle;
    public $MandN;
    public $Security;
    public $Session;

    /**
     * load dynamic html-template
     *
     * @param string $fileLink
     * @return string
     */
    public function loadTemplate($filelink) {
        return (file_exists($filelink)) ? $this->Filehandle->readFilecontent($filelink) : "";
    }

    /**
     * setter-method for controller-env
     *
     * @param string $strExtName
     * @param string $strInstanceId
     * @param string $strExtTemplate
     * @param object $objConfiguration
     * @param array $arrLangs
     * @param string $strAlpha2
     * @param object $objRequest
     * @param object $Filehandle
     * @param object $MandN
     * @param object $Security
     * @param object $Session
     */
    public function setController($strExtName, $strInstanceId, $strExtTemplate, $objConfiguration, $arrLangs, $strAlpha2,  Request $objRequest,
                                  $Filehandle, $MandN, $Security, $Session) {
        $this->strExtName = $strExtName;
        $this->strInstanceId = $strInstanceId;
        $this->strExtTemplate = $strExtTemplate;
        $this->objConfiguration = $objConfiguration;
        $this->arrLangs = $arrLangs;
        $this->strAlpha2 = $strAlpha2;
        $this->objRequest = $objRequest;

        $this->Filehandle = $Filehandle;
        $this->MandN = $MandN;
        $this->Security = $Security;
        $this->Session = $Session;
    }

    /**
     * render AJAX-calls of frontend-env (angular.js)
     *
     * @param string $strOutputAjax
     */
    public function renderAjax($strOutputAjax) {
        echo $strOutputAjax;
        exit;
    }

    /**
     * abstract definition of action-method, entrance from view to controller
     */
    abstract function action();
}