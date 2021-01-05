<?php
/*
 * definition of MVC-Controller basic-class
 *
 * @author    Gunnar von Spreckelsen <service@ihrwebentwickler.de>
 * @package   delightos
 */

namespace delightnet\delightos;

interface ControllerInterface {
    public function loadTemplate(string $filelink);

    public function setController(string $strExtName, string $strInstanceId, string $strExtTemplate,
                                  object $objConfiguration, object $objLangs, string $strAlpha2, Request $objRequest,
                                  Filehandle $Filehandle, MandN $MandN, Security $Security, Session $Session);

    public function renderAjax(string $strOutputAjax);

    public function action();
}