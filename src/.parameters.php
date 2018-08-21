<?php

use Bitrix\Main\Localization\Loc;

return array(
    array(
        'DIV'     => 'edit',
        'TAB'     => Loc::getMessage('tab_common'),
        'TITLE'   => Loc::getMessage('tab_common'),
        'OPTIONS' => array(
            array(
                'key',
                Loc::getMessage('recaptcha_key'),
                '',
                array('text', 40)
            ),
            array(
                'secret',
                Loc::getMessage('recaptcha_secret'),
                '',
                array('text', 40)
            ),
        ),
    ),
);