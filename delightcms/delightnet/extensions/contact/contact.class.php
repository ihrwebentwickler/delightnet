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
use delightnet\delightos\PHPMailer;

class Contact {
    public $objPhpmailer;
    public $intNbCharsOfCaptcha;
    public $strSigns;

    public $MandN;
    public $Security;
    public $Session;

    public function __construct($MandN, $Security, $Session) {
        $this->objPhpmailer = new PHPMailer();
        $this->intNbCharsOfCaptcha = 4;
        $this->strSigns = "---aABcCDeEfFGhHjJkKLmMnNpPRsSuUvVwWxXyYzZ2345678---";

        $this->MandN = $MandN;
        $this->Security = $Security;
        $this->Session = $Session;
    }

    public function checkCaptcha($strRequestCaptcha) {
        $strSessionCaptchaKey = $this->Session->getSession('recaptchakey');
        $strSessionCaptchaKey = $this->Security->decodeString($strSessionCaptchaKey);

        if ($strRequestCaptcha == "" || $strSessionCaptchaKey == "") {
            return false;
        }

        $arraySpamKey = explode("_", $strSessionCaptchaKey);
        $strKeyClearText = "";
        foreach ($arraySpamKey as $key => $value) {
            if ($value && $value != "_" && strlen($strKeyClearText) < $this->intNbCharsOfCaptcha) {
                $value = $value + 3;
                $strKeyClearText .= $this->strSigns{$value};
            }
        }

        if ($strKeyClearText != $strRequestCaptcha) {
            return false;
        }

        return true;
    }

    public function checkForm($arrayHtmlEmailData, $objConfiguration, $strInstanceId) {
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

    public function SetMessageTextMarker($messageText, $objRequest) {
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

    public function getCaptcha($objConfiguration, $strInstanceId) {
        $font = "public/extensions/contact/fonts/" . $objConfiguration->contact->captcha->{$strInstanceId}->font;
        $imageWidth = $objConfiguration->contact->captcha->{$strInstanceId}->number * $objConfiguration->contact->captcha->{$strInstanceId}->fontSize + 4;
        $imageHeight = 2 * $objConfiguration->contact->captcha->{$strInstanceId}->fontSize - 4;
        $image = imagecreate($imageWidth, $imageHeight);
        $arrayRgbBackgroundColor = $this->MandN->hex2rgb($objConfiguration->contact->captcha->{$strInstanceId}->backgroundColor);
        imagecolorallocate($image, $arrayRgbBackgroundColor[0], $arrayRgbBackgroundColor[1], $arrayRgbBackgroundColor[2]);
        $arrayRgbFontColors = $this->MandN->hex2rgb($objConfiguration->contact->captcha->{$strInstanceId}->fontColor);

        $strCaptchaKey = "";
        $left = -11;

        for ($i = 1; $i <= $this->intNbCharsOfCaptcha; $i++) {
            $signCaptchaKey = rand(4, strlen($this->strSigns) - 7) - 3;
            $strCaptchaKey .= $signCaptchaKey . "_";

            $angle = (rand(1, 2) == 1) ? rand(-4, -2) : rand(2, 4);
            ImageTTFText($image, $objConfiguration->contact->captcha->{$strInstanceId}->fontSize, $angle, $left + (14 * $i), 18, imagecolorallocate($image, $arrayRgbFontColors[0], $arrayRgbFontColors[1], $arrayRgbFontColors[2]), $font, $this->strSigns{$signCaptchaKey + 3});
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

    public function sendMail($arrayHtmlEmail, $objConfiguration, $objRequest, $strInstanceId) {
        $messageText = $this->SetMessageTextMarker($objConfiguration->contact->main->{$strInstanceId}->messagetext, $objRequest);

        foreach ($arrayHtmlEmail as $key => $value) {
            $messageText = $this->MandN->setBlock($messageText, $key, $value);
        }

        $objConfiguration->contact->main->{$strInstanceId}->emailTo = (isset($objConfiguration->contact->main->{$strInstanceId}->emailTo)) ? $objConfiguration->contact->main->{$strInstanceId}->emailTo : "";
        $objConfiguration->contact->main->{$strInstanceId}->subject = (isset($objConfiguration->contact->main->{$strInstanceId}->subject)) ? $objConfiguration->contact->main->{$strInstanceId}->subject : "";

        $this->objPhpmailer->From = "automat@" . $objRequest->getServerName();
        $this->objPhpmailer->FromName = "Webseiten-Service";
        $this->objPhpmailer->AddAddress(utf8_decode($objConfiguration->contact->main->{$strInstanceId}->emailTo));
        $this->objPhpmailer->Subject = utf8_decode($objConfiguration->contact->main->{$strInstanceId}->subject);
        $this->objPhpmailer->Body = utf8_decode($messageText);
        $this->objPhpmailer->Send();
    }
}