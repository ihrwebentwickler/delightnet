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

    public function setController(string $strExtName, int $instanceId, string $strExtTemplate,
                                  object $objConfiguration, object $objLangs, string $strAlpha2, Request $objRequest,
                                  Filehandle $Filehandle, MandN $MandN, Security $Security, Session $Session);

    public function renderContent(string $strOutput);

    public function action();
}