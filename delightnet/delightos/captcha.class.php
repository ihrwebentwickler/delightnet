<?php

/*
 * env for generating captchas
 *
 * @author    Gunnar von Spreckelsen <service@ihrwebentwickler.de>
 * @package   delightos
 *
 */

namespace delightnet\delightos;

class Captcha {
    public Session $Session;
    public Security $Security;
    public string $strSigns;
    public object $captchaData;
    /**
     * @var MandN
     */
    public MandN $MandN;

    public function __construct() {
        $this->Session = new Session();
        $this->Security = new Security();
        $this->strSigns = "aABcCDeEfFGhHjJkKLmMnNpPRsSuUvVwWxXyYzZ2345678";
        $this->MandN = new MandN(new Filehandle(), new HttpRequest());

        $this->captchaData = (object)[
            'nbCharsOfCaptcha' => 4,
            'font' => '232MKRB_.TTF',
            'fontSize' => 17,
            'fontColor' => 'f49600',
            'backgroundColor' => 'F0F0F1'
        ];
    }

    function setCaptcha($captchaData): void {
        $this->captchaData = $captchaData;
    }

    function getCaptcha(): object {
        return $this->captchaData;
    }

    /**
     * test of valid captcha-Input
     * @param string $strCaptchaInput
     * @return bool
     */
    function checkCaptcha(string $strCaptchaInput): bool {
        $strSessionCaptchaKey = $this->Session->getSession('recaptchakey');
        $strSessionCaptchaKey = $this->Security->decodeString($strSessionCaptchaKey);
        $isValid = true;

        if ($strCaptchaInput === "" || $strSessionCaptchaKey === "") {
            $isValid = false;
        }

        if ($isValid) {
            $arraySpamKey = explode("_", $strSessionCaptchaKey);
            $strKeyClearText = "";

            if (!is_array($arraySpamKey) || count($arraySpamKey) !== 5) {
                $isValid = false;
            } else {
                foreach ($arraySpamKey as $key => $value) {
                    if ($value && $value != "_" && strlen($strKeyClearText) < $this->captchaData->nbCharsOfCaptcha) {
                        $strKeyClearText .= $this->strSigns[$value - 1];
                    }
                }

                if ($strKeyClearText != $strCaptchaInput) {
                    $isValid = false;
                }
            }
        }

        return $isValid;
    }

    /**
     * get dynamic html-captcha
     * @return string
     */
    public function getCaptchaImage(): string {
        $font = "public/extensions/contact/fonts/" . $this->captchaData->font;
        $imageWidth = $this->captchaData->nbCharsOfCaptcha * $this->captchaData->fontSize + 4;
        $imageHeight = 2 * $this->captchaData->fontSize - 4;
        $image = imagecreate($imageWidth, $imageHeight);
        $arrayRgbBackgroundColor =
            $this->MandN->hex2rgb($this->captchaData->backgroundColor);

        imagecolorallocate(
            $image,
            $arrayRgbBackgroundColor[0],
            $arrayRgbBackgroundColor[1],
            $arrayRgbBackgroundColor[2]
        );

        $arrayRgbFontColors = $this->MandN->hex2rgb($this->captchaData->fontColor);
        $strCaptchaKey = "";
        $left = -11;

        for ($i = 1; $i <= $this->captchaData->nbCharsOfCaptcha; $i++) {
            $nbCaptchaKey = (int)rand(1, strlen($this->strSigns));
            $strCaptchaKey .= $nbCaptchaKey . "_";

            $angle = (rand(1, 2) == 1) ? rand(-4, -2) : rand(2, 4);
            ImageTTFText(
                $image,
                $this->captchaData->fontSize,
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
}