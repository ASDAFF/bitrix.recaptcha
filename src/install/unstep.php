<?php

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

if(!check_bitrix_sessid()){
    return;
}

echo CAdminMessage::ShowNote(
    Loc::getMessage("BEFORE")." ".Loc::getMessage("AFTER")
);

?>

<form action="<?=$APPLICATION->GetCurPage();?>">
    <input type="hidden" name="lang" value="<?=LANG;?>">
    
    <input type="submit" value="<?=Loc::getMessage("SUBMIT_BACK");?>">
</form>