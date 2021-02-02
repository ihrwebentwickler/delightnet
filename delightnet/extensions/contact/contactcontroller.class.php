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
    /**
     * init action into controller-class
     *
     * @return string
     */
    public function action(): string {
        $objContact = new Contact($this->MandN, $this->Security, $this->Session);
        $this->replaceMapsDataEnv();
        $htmlEmailData = $this->objRequest->getParameter('emaildata');
        $htmlEmailData = is_array($htmlEmailData) ?
            $this->Security->undoTags($htmlEmailData) : strip_tags($htmlEmailData);
        $isValidCaptcha = false;

        if ($this->objRequest->getParameter('contactform')) {
            $isValidCaptcha = $objContact->checkCaptcha($this->objRequest->getParameter('captcha'));
            $isValidForm = $objContact->checkForm($htmlEmailData, $this->objConfiguration, $this->instanceId);

            if ($isValidCaptcha && $isValidForm) {
                $objContact->sendMail(
                    $htmlEmailData,
                    $this->objConfiguration, $this->objRequest, $this->instanceId
                );

                $this->objRequest->loadLocation($this->objConfiguration->contact->main
                    ->{$this->instanceId}->ConfirmationSiteFileName);
                exit;
            }
        }

        if ($this->objRequest->getParameter("query") == "getCaptchaimage") {
            $htmlCaptcha = (file_exists("public/extensions/contact/template/parts/spamkeyimage.tpl")) ?
                $this->Filehandle->readFilecontent("public/extensions/contact/template/parts/spamkeyimage.tpl") : null;
            $strSpamkey = $objContact->getCaptcha($this->objConfiguration, $this->instanceId);
            $this->renderContent($this->MandN->setBlock($htmlCaptcha, "IMGDATA", $strSpamkey));
        }

        $this->replaceInputData($htmlEmailData);
        $this->replaceSpamInputError($isValidCaptcha);

        return $this->strExtTemplate;
    }

    /**
     * init action into controller-class
     *
     * @param $htmlEmailData
     * @return void
     */
    public function replaceInputData($htmlEmailData): void {
        if (is_object($this->objConfiguration->contact->formParameters->{$this->instanceId})) {
            foreach ($this->objConfiguration->contact->formParameters->{$this->instanceId} as $key => $value) {
                $htmlStringValue =
                    (
                        isset($htmlEmailData)
                        && isset($htmlEmailData[$key])
                        && $htmlEmailData[$key] != "{DATA" . $key . "}"
                    ) ?
                        $htmlEmailData[$key] : "";
                $this->strExtTemplate = $this->MandN->setBlock($this->strExtTemplate, "DATA" . $key, $htmlStringValue);

                $this->strExtTemplate = $this->MandN->setBlock(
                    $this->strExtTemplate, "ERROR" . $key,
                    $this->objConfiguration->contact->formParameters->{$this->instanceId}->{$key}->error
                );
            }
        }
    }

    /**
     * set maps-values from configuration to html
     *
     * @return void
     */
    public function replaceMapsDataEnv(): void {
        $this->strExtTemplate = $this->MandN->setBlock($this->strExtTemplate, "MAP_WELCOMETEXT", $this->objConfiguration->contact->map->{$this->instanceId}->welcometext);
        $this->strExtTemplate = $this->MandN->setBlock($this->strExtTemplate, "MAP_ACTIONTEXT", $this->objConfiguration->contact->map->{$this->instanceId}->actiontext);
        $this->strExtTemplate = $this->MandN->setBlock($this->strExtTemplate, "MAP_LATITUDE", $this->objConfiguration->contact->map->{$this->instanceId}->latitude);
        $this->strExtTemplate = $this->MandN->setBlock($this->strExtTemplate, "MAP_LONGITUDE", $this->objConfiguration->contact->map->{$this->instanceId}->longitude);
        $this->strExtTemplate = $this->MandN->setBlock($this->strExtTemplate, "MAP_ZOOM", $this->objConfiguration->contact->map->{$this->instanceId}->zoom);
        $this->strExtTemplate = $this->MandN->setBlock($this->strExtTemplate, "MAP_WIDTH", $this->objConfiguration->contact->map->{$this->instanceId}->width);
        $this->strExtTemplate = $this->MandN->setBlock($this->strExtTemplate, "MAP_HEIGHT", $this->objConfiguration->contact->map->{$this->instanceId}->height);
    }

    /**
     * set spam-input-request messages to html
     * @param bool $isValidCaptcha
     * @return void
     */
    public function replaceSpamInputError(bool $isValidCaptcha): void {
        if (!$isValidCaptcha && $this->objRequest->getParameter('captcha')) {
            $htmlErrorBorder = $this->loadTemplate("public/extensions/contact/template/parts/errorborder.tpl");
        } else {
            $htmlErrorBorder = "";
        }

        $this->strExtTemplate = $this->MandN->setBlock($this->strExtTemplate, "STYLE_KEYERROR", $htmlErrorBorder);
    }
}