<?php

namespace delightnet\delightcms;

use delightnet\delightos\Request;
use delightnet\delightos\Response;

interface CmsTemplateView {

    public function setCmsCommandFile($cmd);

    public function getandSetSessionAndCmdEnvirement($cmd, $user, $password);

    public function readDynamicCmsContentFile();

    public function replaceDynamicCmsContent();

    public function replaceBreadCrumbText();

    public function readAndReplaceLoginSite();

    public function replaceDynamicCmsFileIntegration();
    
    public function setLanguageTranslation();

    public function ReplaceLanguageTranslation();
    
    public function replaceExtensionHelpMenuEntries();

    public function replaceExtensionConfMenuEntries();
    
    public function replaceLanguage();

    public function buildCmsTemplate();
}