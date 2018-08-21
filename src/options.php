<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\HttpApplication;
use Bitrix\Main\Loader;
use Bitrix\Main\Config\Option;

Loc::loadMessages(__FILE__);

$request = HttpApplication::getInstance()
    ->getContext()
    ->getRequest();
$moduleId = htmlspecialcharsbx(
    $request["mid"] != "" 
        ? $request["mid"] 
        : $request["id"]
);
$curPage = $APPLICATION->GetCurPage();

Loader::includeModule($moduleId);

$aTabs = include_once __DIR__ . '/.parameters.php';

/* 
 * Save options if the POST request method 
 * and redirect to the options page 
 */
if($request->isPost() && check_bitrix_sessid()){

    foreach($aTabs as $aTab) {

        foreach($aTab["OPTIONS"] as $arOption){

            if(!is_array($arOption)){
                continue;
            }

            if($arOption["note"]){
                continue;
            }

            if($request["apply"]){

                $optionValue = $request->getPost($arOption[0]);

                if($arOption[0] == "switch_on"){

                    if($optionValue == ""){

                        $optionValue = "N";
                    }
                }

                Option::set($moduleId, $arOption[0], is_array($optionValue) ? implode(",", $optionValue) : $optionValue);
            } elseif ($request["default"]) {

                Option::set($moduleId, $arOption[0], $arOption[2]);
            }
        } // endforeach $aTab["OPTIONS"]
    } // endforeach $aTabs

    LocalRedirect($curPage."?mid=".$moduleId."&lang=".LANG);
}

/* 
 * Draw form for editing options 
 */
$tabControl = new CAdminTabControl(
    "tabControl",
    $aTabs
);

$tabControl->Begin(); ?>

<form action="<?=$curPage;?>?mid=<?=$moduleId;?>&lang=<?=LANG;?>" method="post">

    <? foreach ($aTabs as $aTab) {
        if ($aTab["OPTIONS"]) {
            $tabControl->BeginNextTab();
            __AdmSettingsDrawList($moduleId, $aTab["OPTIONS"]);
        }
    }

    $tabControl->Buttons(); ?>

    <input type="submit" name="apply" value="<?=Loc::GetMessage("apply");?>" class="adm-btn-save">
    <input type="submit" name="default" value="<?=Loc::GetMessage("default");?>">
    <?=bitrix_sessid_post();?>

</form>

<? $tabControl->End(); ?>