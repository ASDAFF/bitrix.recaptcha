<?php

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

if (!check_bitrix_sessid()) {
    return;
}

if ($errorException = $APPLICATION->GetException()) {

    echo CAdminMessage::ShowMessage(
        $errorException->GetString()
    );

} else {

    echo CAdminMessage::ShowNote(
        Loc::getMessage("BEFORE") . " " . Loc::getMessage("AFTER")
    );

}

?>

<form action="<?=$APPLICATION->GetCurPage();?>">
    <input type="hidden" name="lang" value="<?=LANG;?>" />
    <input type="hidden" name="id" value="<?=GetModuleID(__FILE__);?>">
    <input type="hidden" name="install" value="Y" />

    <input type="submit" value="<?=Loc::getMessage("SUBMIT_BACK");?>">
</form>