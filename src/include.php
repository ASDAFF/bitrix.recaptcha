<?php
use Bitrix\Main\Page\Asset;
Asset::getInstance()->addJs('https://www.google.com/recaptcha/api.js');

Bitrix\Main\Loader::registerAutoloadClasses(
    "inteldev.recaptcha",
    array(
        "Inteldev\\ReCaptcha\\Main" => "lib/main.php",
    )
);