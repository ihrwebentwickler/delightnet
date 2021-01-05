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
    public string $strExtName;
    public int $strInstanceId;
    public string $strExtTemplate;
    public object $objConfiguration;
    public object $objLangs;
    public string $strAlpha2;
    public Request $objRequest;

    public Filehandle $Filehandle;
    public MandN $MandN;
    public Security $Security;
    public Session $Session;

    /**
     * load dynamic html-template
     *
     * @param string $filelink
     * @return string
     */
    public function loadTemplate(string $filelink): string {
        return (file_exists($filelink)) ? $this->Filehandle->readFilecontent($filelink) : "";
    }

    /**
     * setter-method for controller-env
     *
     * @param string $strExtName
     * @param string $strInstanceId
     * @param string $strExtTemplate
     * @param object $objConfiguration
     * @param object $objLangs
     * @param string $strAlpha2
     * @param Request $objRequest
     * @param Filehandle $Filehandle
     * @param MandN $MandN
     * @param Security $Security
     * @param Session $Session
     */
    public function setController(string $strExtName, string $strInstanceId, string $strExtTemplate,
                                  object $objConfiguration, object $objLangs, string $strAlpha2, Request $objRequest,
                                  Filehandle $Filehandle, MandN $MandN, Security $Security, Session $Session) {
        $this->strExtName = $strExtName;
        $this->strInstanceId = $strInstanceId;
        $this->strExtTemplate = $strExtTemplate;
        $this->objConfiguration = $objConfiguration;
        $this->objLangs = $objLangs;
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
     *  @return void
     */
    public function renderAjax(string $strOutputAjax): void {
        echo $strOutputAjax;
        exit;
    }

    /**
     * abstract definition of action-method, entrance from view to controller
     */
    abstract function action();
}