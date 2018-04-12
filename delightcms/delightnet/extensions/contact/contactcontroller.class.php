<?php

/*
 * contact-form controller including spamkey-building and checking
 * 
 * @author    Gunnar von Spreckelsen <service@ihrwebentwickler.de>
 * @package   delightos
 * @version   5.30
 * 
 */
namespace delightnet\extensions\Contact;
use delightnet\delightos\Controller;

class ContactController extends Controller {
    public function action() {
        $objContact = new Contact($this->MandN, $this->Security, $this->Session);
        $this->replaceGoogleMapsData();
        $arrayHtmlEmailData = $this->Security->undoTags($this->objRequest->getParameter('emaildata'));
        $isValidCaptcha = false;

        if ($this->objRequest->getParameter('contactform')) {
            $isValidCaptcha = $objContact->checkCaptcha($this->objRequest->getParameter('captcha'));
            $isValidForm = $objContact->checkForm($arrayHtmlEmailData, $this->objConfiguration, $this->strInstanceId);

            if ($isValidCaptcha === true && $isValidForm === true) {
                $objContact->sendMail($arrayHtmlEmailData, $this->objConfiguration, $this->objRequest, $this->strInstanceId);
                $this->objRequest->loadLocation($this->objConfiguration->contact->main->{$this->strInstanceId}->ConfirmationSiteFileName);
                exit;
            }
        }

        if ($this->objRequest->getParameter("query") == "getCaptchaimage") {
            $htmlCaptcha = (file_exists("public/extensions/contact/template/parts/spamkeyimage.tpl")) ? $this->Filehandle->readFilecontent("public/extensions/contact/template/parts/spamkeyimage.tpl") : "";
            $strSpamkey = $objContact->getCaptcha($this->objConfiguration, $this->strInstanceId);
            $this->renderAjax($this->MandN->setBlock($htmlCaptcha, "IMGDATA", $strSpamkey));
        }

        $this->replaceInputData($arrayHtmlEmailData);
        $this->replaceSpamInputError($isValidCaptcha);
        return $this->strExtTemplate;
    }

    public function replaceInputData($arrayHtmlEmailData) {
        if (is_object($this->objConfiguration->contact->formParameters->{$this->strInstanceId})) {
            foreach ($this->objConfiguration->contact->formParameters->{$this->strInstanceId} as $key => $value) {
                $htmlStringValue = (isset($arrayHtmlEmailData[$key]) && $arrayHtmlEmailData[$key] != "{DATA" . $key . "}") ? $arrayHtmlEmailData[$key] : "";
                $this->strExtTemplate = $this->MandN->setBlock($this->strExtTemplate, "DATA" . $key, $htmlStringValue);

                $this->strExtTemplate = $this->MandN->setBlock($this->strExtTemplate, "ERROR" . $key, $this->objConfiguration->contact->formParameters->{$this->strInstanceId}->{$key}->error);
            }
        }
    }

    public function replaceGoogleMapsData() {
        $this->strExtTemplate = $this->MandN->setBlock($this->strExtTemplate, "GOOGLEMAP_WELCOMETEXT", $this->objConfiguration->contact->googlemap->{$this->strInstanceId}->welcometext);
        $this->strExtTemplate = $this->MandN->setBlock($this->strExtTemplate, "GOOGLEMAP_ACTIONTEXT", $this->objConfiguration->contact->googlemap->{$this->strInstanceId}->actiontext);
        $this->strExtTemplate = $this->MandN->setBlock($this->strExtTemplate, "GOOGLEMAP_LATITUDE", $this->objConfiguration->contact->googlemap->{$this->strInstanceId}->latitude);
        $this->strExtTemplate = $this->MandN->setBlock($this->strExtTemplate, "GOOGLEMAP_LONGITUDE", $this->objConfiguration->contact->googlemap->{$this->strInstanceId}->longitude);
        $this->strExtTemplate = $this->MandN->setBlock($this->strExtTemplate, "GOOGLEMAP_ZOOM", $this->objConfiguration->contact->googlemap->{$this->strInstanceId}->zoom);
        $this->strExtTemplate = $this->MandN->setBlock($this->strExtTemplate, "GOOGLEMAP_WIDTH", $this->objConfiguration->contact->googlemap->{$this->strInstanceId}->width);
        $this->strExtTemplate = $this->MandN->setBlock($this->strExtTemplate, "GOOGLEMAP_HEIGHT", $this->objConfiguration->contact->googlemap->{$this->strInstanceId}->height);
    }

    public function replaceSpamInputError($isValidCaptcha) {
        if ($isValidCaptcha == false && $this->objRequest->getParameter('captcha')) {
            $htmlErrorBorder = $this->loadTemplate("public/extensions/Contact/template/parts/errorborder.tpl");
        } else {
            $htmlErrorBorder = "";
        }

        $this->strExtTemplate = $this->MandN->setBlock($this->strExtTemplate, "STYLE_KEYERROR", $htmlErrorBorder);
    }
}