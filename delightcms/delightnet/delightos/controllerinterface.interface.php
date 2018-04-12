<?php
/*
 * definition of MVC-Controller basic-class
 *
 * @author    Gunnar von Spreckelsen <service@ihrwebentwickler.de>
 * @package   delightos
 */
namespace delightnet\delightos;

interface ControllerInterface {
    public function loadTemplate($filelink);

    public function setController($strExtName, $strInstanceId, $strExtTemplate, $objConfiguration, $arrLangs, $strAlpha2, Request $objRequest,
                                  $Filehandle, $MandN, $Security, $Session);

    public function renderAjax($strOutputAjax);

    public function action();
}