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

use delightnet\delightos\Registry;
use delightnet\delightos\Request;

class Contact {

    public $pluginNb;
    public $Mandn;
    public $Filehandle;
    public $arrayContact;
    public $contactTemplate;
    public $partContact;
    public $phpmailer;
    private $signs;

    public function __construct($configuration) {
        $this->arrayContact = $configuration;

        $this->contactTemplate = Registry::get('Filehandle')->readFilecontent("public/extensions/Contact/template/Contact.tpl");
        $this->partContact = Registry::get('Filehandle')->readFilecontent("public/extensions/Contact/template/parts/Contact.tpl");
        $this->phpmailer = new \delightnet\delightos\PHPMailer();

        $this->signs = "---aABcCDeEfFGhHjJkKLmMnNpPRsSuUvVwWxXyYzZ2345678---";
    }

    public function setStandardMarker() {
        $this->contactTemplate = Registry::get('Mandn')->setBlock($this->contactTemplate, "PART_KONTAKT", $this->partContact);
        $this->contactTemplate = Registry::get('Mandn')->setBlock($this->contactTemplate, "STYLE_INPUT_FIELD_ERROR", $this->arrayContact["message"]["styleInputFieldError"]);

        $this->contactTemplate = Registry::get('Mandn')->setBlock($this->contactTemplate, "GOOGLEMAP_LATLNG", $this->arrayContact["googlemaps"]["latlng"]);
        $this->contactTemplate = Registry::get('Mandn')->setBlock($this->contactTemplate, "GOOGLEMAP_ZOOM", $this->arrayContact["googlemaps"]["zoom"]);
        $this->contactTemplate = Registry::get('Mandn')->setBlock($this->contactTemplate, "GOOGLEMAP_WIDTH", $this->arrayContact["googlemaps"]["width"]);
        $this->contactTemplate = Registry::get('Mandn')->setBlock($this->contactTemplate, "GOOGLEMAP_HEIGHT", $this->arrayContact["googlemaps"]["height"]);
        $this->contactTemplate = Registry::get('Mandn')->setBlock($this->contactTemplate, "GOOGLEMAP_POSITION", $this->arrayContact["googlemaps"]["position"]);


        $this->contactTemplate = Registry::get('Mandn')->setBlock($this->contactTemplate, "CSS_ERROR", $this->arrayContact["message"]["cssError"]);
        $this->contactTemplate = Registry::get('Mandn')->setBlock($this->contactTemplate, "MESSAGE_START", strtr($this->arrayContact["message"]['messageStart'], "'", "&apos;"));
        $this->contactTemplate = Registry::get('Mandn')->setBlock($this->contactTemplate, "STANDARD_ERROR", strtr($this->arrayContact["message"]["standardError"], "'", "&apos;"));
    }

    public function startContact($pluginnb, Request $request) {
        $this->pluginNb = $pluginnb;
        $this->setStandardMarker();

        $arrayHtmlEmailData = $request->getParameter('emaildata');

        if (sizeof($arrayHtmlEmailData) > 1) {
            $arrayHtmlEmailData = Registry::get('Security')->undoTags($arrayHtmlEmailData);
        }
        
        $strMessage = "messageStart";

        $this->replaceJsDomData();

        $this->generateCaptcha($this->contactTemplate);

        if ($strMessage == "messageStart" && $request->getParameter("submit")) {
            if (sizeof($arrayHtmlEmailData) > 1) {
                $strMessage = $this->checkRequestAndReplaceHTMLFormData($arrayHtmlEmailData);
            }
            if ($strMessage != "standardError" && sizeof($arrayHtmlEmailData) > 1) {
                $strMessage = $this->checkCaptcha($request->getParameter('captcha'), $request->getParameter('captchaKey'));
            }
        }

        if ($strMessage == "send") {
            $this->sendMail($request->getParameter('emaildata'), $request);
            $strMessage = "messageStart";
        }

        $this->arrayContact["message"][$strMessage] = (isset($this->arrayContact["message"][$strMessage])) ?
                strtr($this->arrayContact["message"][$strMessage], "'", "&apos;") : "";
        $this->contactTemplate = Registry::get('Mandn')->setBlock($this->contactTemplate, "CONTACT_MESSAGE", strtr($this->arrayContact["message"][$strMessage], "'", "&apos;"));

        if ($strMessage == "spamKeyError") {
            $strSpamKeyErrorStyle = $this->arrayContact["message"]["cssError"];
        } else {
            $strSpamKeyErrorStyle = "";
        }

        $this->contactTemplate = Registry::get('Mandn')->setBlock($this->contactTemplate, "CONTACT_STYLE_ERROR", $strSpamKeyErrorStyle);
        return $this->contactTemplate;
    }

    public function replaceJsDomData() {
        if (is_array($this->arrayContact['form'])) {
            $strJsObligation = '[';
            foreach ($this->arrayContact['form'] as $key => $value) {
                if (isset($this->arrayContact["form"][$key]["obligation"])) {
                    $strJsObligation .= "'" . $this->arrayContact["form"][$key]["obligation"] . "'";
                }

                $strJsObligation .= ($key < sizeof($this->arrayContact['form'])) ? ',' : '';
            }
            $strJsObligation .= ']';
            $this->contactTemplate = Registry::get('Mandn')->setBlock($this->contactTemplate, "OBLIGATION", $strJsObligation);
        } else {
            $this->contactTemplate = Registry::get('Mandn')->setBlock($this->contactTemplate, "OBLIGATION", "");
        }
    }

    public function checkRequestAndReplaceHTMLFormData($arrayHtmlEmailData) {
        foreach ($arrayHtmlEmailData as $key => $value) {
            if (isset($this->arrayContact["form"][$key]["obligation"]) && $this->arrayContact["form"][$key]["obligation"] == 1) {
                if ($value == "") {
                    return "standardError";
                }
            }
        }

        return "messageStart";
    }

    public function checkCaptcha($strRequestCaptcha, $strRequestCaptchaKey) {
        if ($strRequestCaptcha == "" || $strRequestCaptchaKey == "") {
            return "spamKeyError";
        }

        $arraySpamKey = explode("_", $strRequestCaptchaKey);

        $strKeyClearText = "";
        foreach ($arraySpamKey as $key => $value) {
            if ($value && $value != "_") {
                $value = $value + 3;
                $strKeyClearText .= $this->signs{$value};
            }
        }

        if ($strKeyClearText != $strRequestCaptcha) {
            return "spamKeyError";
        }

        return "send";
    }

    public function generateCaptcha($template) {
        $font = "public/extensions/Contact/fonts/" . $this->arrayContact["captcha"]["font"];
        $imagewidth = $this->arrayContact["captcha"]["number"] * $this->arrayContact["captcha"]["fontSize"] + 6;
        $imageheight = $this->arrayContact["captcha"]["fontSize"] + 24;
        $image = imagecreate($imagewidth, $imageheight);

        $arrayRgbBackgroundColor = \delightnet\delightcms\Gui::hex2rgb($this->arrayContact["captcha"]["backgroundColor"]);
        imagecolorallocate($image, $arrayRgbBackgroundColor[0], $arrayRgbBackgroundColor[1], $arrayRgbBackgroundColor[2]);

        $arrayRgbFontColors = \delightnet\delightcms\Gui::hex2rgb($this->arrayContact["captcha"]["fontColor"]);
        $strCaptchaKey = "";
        $left = -11;

        for ($i = 1; $i <= 4; $i++) {
            $signPositionOrign = rand(4, strlen($this->signs) - 7);
            $signPositionMoved = $signPositionOrign - 3;
            $strCaptchaKey .= $signPositionMoved . "_";

            $randomLeftAngleOrRightAngle = rand(1, 2);
            $angle = ($randomLeftAngleOrRightAngle == 1) ? rand(-4, -2) : rand(2, 4);
            ImageTTFText($image, $this->arrayContact["captcha"]["fontSize"], $angle, $left + (15 * $i), 15, imagecolorallocate($image, $arrayRgbFontColors[0], $arrayRgbFontColors[1], $arrayRgbFontColors[2]), $font, $this->signs{$signPositionOrign});
        }
        
        $linecolor = imagecolorallocate($image,203,203,205);
        imageline($image, rand(1,3), rand(3,8), rand(30,55), rand(8,14), $linecolor);
        
        ob_start();
        ImagePNG($image);
        imagedestroy($image);
        $imageOutput = ob_get_contents();
        $imageOutput = base64_encode($imageOutput);
        ob_end_clean();

        $htmlCaptchaOutput = '<img src="data:image/png;base64,' . $imageOutput . '" alt="">';
        $this->contactTemplate = Registry::get('Mandn')->setBlock($this->contactTemplate, "CAPTCHAIMAGE", $htmlCaptchaOutput);
        $this->contactTemplate = Registry::get('Mandn')->setBlock($this->contactTemplate, "CAPTCHAKEY", $strCaptchaKey);
    }

    public function loadConfirmationTemplate(Request $request) {
        $request->loadNewHeaderLocation($this->arrayContact["email"]["ConfirmationSiteFileName"]);
    }

    private function SetMessageTextMarker($messagetext, Request $request) {
        // Date, if used
        if (strchr($messagetext, "TIMESTAMP")) {
            $messagetext = Registry::get('Mandn')->setBlock($messagetext, "TIMESTAMP", date("d.m.Y H:i", time()));
        }

        // ip-adress, if used
        if (strchr($messagetext, "IP-ADRESS")) {
            $messagetext = Registry::get('Mandn')->setBlock($messagetext, "IP-ADRESS", $request->getIp());
        }

        // servername, if used
        if (strchr($messagetext, "SERVERADRESS")) {
            $messagetext = Registry::get('Mandn')->setBlock($messagetext, "SERVERADRESS", $request->getServerName());
        }

        // host, if used
        if (strchr($messagetext, "HOSTNAME")) {
            $messagetext = Registry::get('Mandn')->setBlock($messagetext, "HOSTNAME", $request->getHttpHost());
        }

        return $messagetext;
    }

    private function sendMail($arrayHtmlEmail, Request $request) {
        $arrayHtmlEmail = Registry::get('Security')->undoTags($arrayHtmlEmail);
        $messagetext = $this->SetMessageTextMarker($this->arrayContact["messagetext"]["messagetext"], $request);

        foreach ($arrayHtmlEmail as $key => $value) {
            $messagetext = Registry::get('Mandn')->setBlock($messagetext, $key, $value);
        }

        $this->arrayContact["email"]["emailTo"] = (isset($this->arrayContact["email"]["emailTo"])) ? $this->arrayContact["email"]["emailTo"] : "";
        $this->arrayContact["email"]["subject"] = (isset($this->arrayContact["email"]["subject"])) ? $this->arrayContact["email"]["subject"] : "";

        $this->phpmailer->From = "automat@" . $request->getServerName();
        $this->phpmailer->FromName = "webseitenservice";
        $this->phpmailer->AddAddress(utf8_decode($this->arrayContact["email"]["emailTo"]));
        $this->phpmailer->Subject = utf8_decode($this->arrayContact["email"]["subject"]);
        $this->phpmailer->Body = utf8_decode($messagetext);

        // $this->phpmailer->Send();

        $this->loadConfirmationTemplate($request);
    }

}