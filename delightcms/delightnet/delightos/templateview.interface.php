<?php
/*
 * Interface-definition of basic-template-class
 * @author    Gunnar von Spreckelsen <service@ihrwebentwickler.de>
 * @package   delightos
 */
namespace delightnet\delightos;

interface TemplateView {
    public function setLangEnv();

    public function setCmd();

    public function setAction();

    public function setDynamicSite();

    public function replaceDynamicFiles();

    public function setThemeEnv();

    public function replaceMultidesign();

    public function replaceMenu();

    public function replaceHtmlData($strObjName);

    public function setGlobalEnv();
}