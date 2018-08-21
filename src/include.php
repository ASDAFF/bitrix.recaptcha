<?php

use Bitrix\Main\Loader;
use Bitrix\Main\EventManager;

$moduleId = 'inteldev.recaptcha';

/*
 * Autoload of module classes
 */
Loader::registerAutoloadClasses(
    $moduleId,
    array(
        'Inteldev\\ReCaptcha\\Main' => 'lib/main.php',
    )
);

/*
 * Connection files of scripting APIs Google reCAPTCHA
 */
EventManager::getInstance()->registerEventHandler('main', 'OnProlog', $moduleId, 'Inteldev\\ReCaptcha\\Main', 'assetJs');