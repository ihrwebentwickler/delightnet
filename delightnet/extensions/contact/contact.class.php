<?php
/*
 * contact-form
 *
 * @author    Gunnar von Spreckelsen <service@ihrwebentwickler.de>
 * @package   delightos
 * @version   5.40
 *
 */

namespace delightnet\extensions\Contact;

use delightnet\delightos\Captcha;
use delightnet\delightos\Filehandle;
use delightnet\delightos\HttpRequest;
use delightnet\delightos\MandN;
use delightnet\delightos\Request;

class Contact {
    public MandN $MandN;
    public HttpRequest $HttpRequest;
    public Captcha $Captcha;

    public function __construct() {
        $this->HttpRequest = new HttpRequest();
        $this->Captcha = new Captcha();
        $this->MandN = new MandN(new Filehandle(), new HttpRequest());
    }

    /**
     * check captcha-user-input
     * @param string $strRequestCaptcha
     * @return bool
     */
    public function checkCaptcha(string $strRequestCaptcha): bool {
        return $this->Captcha->checkCaptcha($strRequestCaptcha);
    }

    /**
     * check requested html-form (validation)
     * @param array $arrayHtmlEmailData
     * @param object $objConfiguration
     * @param string $strInstanceId
     * @return bool
     */
    public function checkForm(array $arrayHtmlEmailData, object $objConfiguration, string $strInstanceId): bool {
        $isValid = true;

        foreach ($arrayHtmlEmailData as $formkey => $formvalue) {
            if (
                isset($objConfiguration->contact->formParameters->{$strInstanceId}->$formkey->obligation)
                && $objConfiguration->contact->formParameters->{$strInstanceId}->$formkey->obligation == 1
                && $formvalue == ""
            ) {
                $isValid = false;
                break;
            }
        }

        return $isValid;
    }

    /**
     * set additional system-values to mail/ html
     * @param string $messageText
     * @param Request $objRequest
     * @return string
     */
    public function SetMessageTextMarker(string $messageText, Request $objRequest): string {
        // Date, if used
        if (strchr($messageText, "TIMESTAMP")) {
            $messageText = $this->MandN->setBlock($messageText, "TIMESTAMP", date("d.m.Y H:i", time()));
        }

        // ip-ADDRESS, if used
        if (strchr($messageText, "IP-ADDRESS")) {
            $messageText = $this->MandN->setBlock($messageText, "IP-ADDRESS", $objRequest->getIp());
        }

        // servername, if used
        if (strchr($messageText, "SERVERADDRESS")) {
            $messageText = $this->MandN->setBlock($messageText, "SERVERADDRESS", $objRequest->getServerName());
        }

        // host, if used
        if (strchr($messageText, "HOSTNAME")) {
            $messageText = $this->MandN->setBlock($messageText, "HOSTNAME", $objRequest->getHttpHost());
        }

        return $messageText;
    }

    /**
     * get dynamic html-captcha
     * @param object $objConfiguration
     * @param string $strInstanceId
     * @return string
     */
    public function getCaptcha(): string {
        return $this->Captcha->getCaptchaImage();
    }

    /**
     * send mail
     * @param array $arrayHtmlEmail
     * @param object $objConfiguration
     * @param Request $objRequest
     * @param string $strInstanceId
     * @return void
     */
    public function sendMail(
        array $arrayHtmlEmail,
        object $objConfiguration,
        Request $objRequest,
        string $strInstanceId
    ): void {
        $messageText = $this->SetMessageTextMarker($objConfiguration->contact->main->{$strInstanceId}->messagetext, $objRequest);

        foreach ($arrayHtmlEmail as $key => $value) {
            $messageText = $this->MandN->setBlock($messageText, $key, $value);
        }

        $objConfiguration->contact->main->{$strInstanceId}->emailTo =
            (isset($objConfiguration->contact->main->{$strInstanceId}->emailTo)) ?
                $objConfiguration->contact->main->{$strInstanceId}->emailTo : "";
        $objConfiguration->contact->main->{$strInstanceId}->subject =
            (isset($objConfiguration->contact->main->{$strInstanceId}->subject)) ?
                $objConfiguration->contact->main->{$strInstanceId}->subject : "";

        $header = utf8_decode("automat@" . $objRequest->getServerName()) . "\r\n";
        $header .= "MIME-Version: 1.0\r\n";
        $header .= "Content-type: text/html; charset=utf-8\r\n";

        $isSend = mail(
            $objConfiguration->contact->main->{$strInstanceId}->emailTo,
            utf8_decode($objConfiguration->contact->main->{$strInstanceId}->subject),
            utf8_decode($messageText),
            $header
        );

        if ($isSend) {
            $this->HttpRequest->loadLocation(
                $objConfiguration->contact->main->{$strInstanceId}->ConfirmationSiteFileName
            );
        }
    }
}