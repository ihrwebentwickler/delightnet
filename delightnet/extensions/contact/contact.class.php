<?php

/*
 * contact-form including spamkey-building and checking
 *
 * @author    Gunnar von Spreckelsen <service@ihrwebentwickler.de>
 * @package   delightos
 * @version   5.30
 *
 */

namespace delightnet\extensions\Contact;

use delightnet\delightos\HttpRequest;
use delightnet\delightos\MandN;
use delightnet\delightos\Request;
use delightnet\delightos\Security;
use delightnet\delightos\Session;

class Contact {
    public int $intNbCharsOfCaptcha;
    public string $strSigns;

    public MandN $MandN;
    public Security $Security;
    public Session $Session;
    public HttpRequest $HttpRequest;

    public function __construct(MandN $MandN, Security $Security, Session $Session) {
        $this->intNbCharsOfCaptcha = 4;
        $this->strSigns = "aABcCDeEfFGhHjJkKLmMnNpPRsSuUvVwWxXyYzZ2345678";

        $this->MandN = $MandN;
        $this->Security = $Security;
        $this->Session = $Session;
        $this->HttpRequest = new HttpRequest();
    }

    /**
     * check captcha-user-input
     * @param string $strRequestCaptcha
     * @return bool
     */
    public function checkCaptcha(string $strRequestCaptcha): bool {
        $strSessionCaptchaKey = $this->Session->getSession('recaptchakey');
        $strSessionCaptchaKey = $this->Security->decodeString($strSessionCaptchaKey);
        $isValid = true;

        if ($strRequestCaptcha === "" || $strSessionCaptchaKey === "") {
            $isValid = false;
        }

        if ($isValid) {
            $arraySpamKey = explode("_", $strSessionCaptchaKey);
            $strKeyClearText = "";

            if (!is_array($arraySpamKey) || count($arraySpamKey) !== 5) {
                $isValid = false;
            } else {
                foreach ($arraySpamKey as $key => $value) {
                    if ($value && $value != "_" && strlen($strKeyClearText) < $this->intNbCharsOfCaptcha) {
                        $strKeyClearText .= $this->strSigns[$value - 1];
                    }
                }

                if ($strKeyClearText != $strRequestCaptcha) {
                    $isValid = false;
                }
            }
        }

        return $isValid;
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
    public function getCaptcha(object $objConfiguration, string $strInstanceId): string {
        $font = "public/extensions/contact/fonts/" . $objConfiguration->contact->captcha->{$strInstanceId}->font;
        $imageWidth = $this->intNbCharsOfCaptcha * $objConfiguration->contact->captcha->{$strInstanceId}->fontSize + 4;
        $imageHeight = 2 * $objConfiguration->contact->captcha->{$strInstanceId}->fontSize - 4;
        $image = imagecreate($imageWidth, $imageHeight);
        $arrayRgbBackgroundColor =
            $this->MandN->hex2rgb($objConfiguration->contact->captcha->{$strInstanceId}->backgroundColor);
        imagecolorallocate(
            $image,
            $arrayRgbBackgroundColor[0],
            $arrayRgbBackgroundColor[1],
            $arrayRgbBackgroundColor[2]
        );
        $arrayRgbFontColors = $this->MandN->hex2rgb($objConfiguration->contact->captcha->{$strInstanceId}->fontColor);

        $strCaptchaKey = "";
        $left = -11;

        for ($i = 1; $i <= $this->intNbCharsOfCaptcha; $i++) {
            $nbCaptchaKey = rand(1, strlen($this->strSigns));
            $strCaptchaKey .= $nbCaptchaKey . "_";

            $angle = (rand(1, 2) == 1) ? rand(-4, -2) : rand(2, 4);
            ImageTTFText(
                $image,
                $objConfiguration->contact->captcha->{$strInstanceId}->fontSize,
                $angle,
                $left + (14 * $i),
                18,
                imagecolorallocate($image, $arrayRgbFontColors[0], $arrayRgbFontColors[1], $arrayRgbFontColors[2]),
                $font,
                $this->strSigns[$nbCaptchaKey - 1]);
        }

        $strCaptchaKey = $this->Security->encodeString($strCaptchaKey);
        $this->Session->setSession('recaptchakey', $strCaptchaKey);

        imageline($image, rand(1, 3), rand(3, 8), rand(30, 55), rand(8, 14), imagecolorallocate($image, 203, 203, 205));
        ob_start();
        ImagePNG($image);
        imagedestroy($image);
        $imageOutput = base64_encode(ob_get_contents());
        ob_end_clean();

        return $imageOutput;
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