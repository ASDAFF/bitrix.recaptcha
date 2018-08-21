<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Config\Option;
use Bitrix\Main\EventManager;
use Bitrix\Main\Application;
use Bitrix\Main\IO\Directory;

/**
* Подключаем языковые константы
*/
Loc::loadMessages(__FILE__);

/**
* Инсталляция модуля inteldev_geoip
*/
class inteldev_recaptcha extends CModule
{
    public $errors;

    /**
    * Инициализация модуля для страницы «Управление модулями»
    */
    public function __construct()
    {
        $this->MODULE_ID           = str_replace('_', '.', get_class($this));
        $this->MODULE_NAME         = Loc::getMessage('NAME');
        $this->MODULE_DESCRIPTION  = Loc::getMessage('DESCRIPTION');
        $this->PARTNER_NAME        = Loc::getMessage('PARTNER_NAME');
        $this->PARTNER_URI         = Loc::getMessage('PARTNER_URI');
        
        if (file_exists(__DIR__ . '/version.php')) {
            $arModuleVersion = array();
            include_once(__DIR__ . '/version.php');
            $this->MODULE_VERSION      = $arModuleVersion['VERSION'];
            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
            
        }
    }

    /**
    * Устанавливаем модуль
    *
    * @return bool
    */ 
    public function DoInstall()
    {
        global $APPLICATION;

        $version = defined('SM_VERSION') ? SM_VERSION : ModuleManager::getVersion('main');

        if (CheckVersion($version, '14.00.00')) {

            $this->InstallFiles();
            $this->InstallDB();
            $this->InstallEvents();

            ModuleManager::registerModule($this->MODULE_ID);

        } else {

            $APPLICATION->ThrowException(
                Loc::getMessage('INSTALL_ERROR_VERSION')
            );

        }

        $APPLICATION->IncludeAdminFile(
            Loc::getMessage('INSTALL_TITLE') . ' "' . Loc::getMessage('NAME') . '"',
            __DIR__ . '/step.php'
        );
        
        return true;
    }

    /**
    * Удаляем модуль
    *
    * @return bool
    */ 
    public function DoUninstall()
    {
        global $APPLICATION;

        $this->UnInstallFiles();
        $this->UnInstallDB();
        $this->UnInstallEvents();

        ModuleManager::unRegisterModule($this->MODULE_ID);

        $APPLICATION->IncludeAdminFile(
            Loc::getMessage('UNINSTALL_TITLE') . ' "' . Loc::getMessage('NAME') . '"',
            __DIR__ . '/unstep.php'
        );
        
        return true;
    }

    /**
    * Добавляем почтовые события
    *
    * @return bool
    */ 
    public function InstallEvents() 
    { 
        return true;
    }

    /**
    * Удаляем почтовые события
    *
    * @return bool
    */ 
    public function UnInstallEvents() 
    {
        return true;
    }

    /**
    * Копируем файлы административной части
    *
    * @return bool
    */ 
    public function InstallFiles() 
    { 
        return true;
    }

    /**
    * Удаляем файлы административной части
    *
    * @return bool
    */ 
    public function UnInstallFiles() 
    { 
        return true;
    }

    /**
    * Добавляем таблицы в БД
    *
    * @return bool
    */ 
    public function InstallDB() 
    { 
        return true;
    }

    /**
    * Удаляем таблицы из БД
    *
    * @return bool
    */ 
    public function UnInstallDB() 
    { 
        // \Option::delete($this->MODULE_ID);
        return true;
    }

}