<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main,
    Bitrix\Main\Loader,
    Bitrix\Main\Config\Option,
    Bitrix\Main\Context,
    Bitrix\Main\Localization\Loc;

CModule::IncludeModule("iblock");
Loc::loadMessages(__FILE__);

class DevelopxBackformComponent extends \CBitrixComponent
{
    protected $arRequest;
    const DEFAULT_CACHE_TIME = 36000000;

    public function onPrepareComponentParams($arParams)
    {
        if (
            $arParams['GOOGLE_CAPTCHA'] == 'Y' &&
            (
                empty($arParams['CAPTCHA_KEY']) ||
                empty($arParams['CAPTCHA_SECRET'])
            )
        ) {
            $arParams['GOOGLE_CAPTCHA'] = 'N';
        }

        if (!isset($arParams["CACHE_TIME"])) {
            $arParams["CACHE_TIME"] = self::DEFAULT_CACHE_TIME;
        }
        return $arParams;
    }

    private function getRequest()
    {
        return Context::getCurrent()->getRequest();
    }

    private function getUser()
    {
        global $USER;
        $user_id = $USER->getId();
        if ($user_id) {
            $rsUser = CUser::GetByID($user_id);
            return $rsUser->Fetch();
        }
        return false;
    }

    private function saveFormOrder()
    {
        $el = new CIBlockElement;

        $props = [];
        foreach ($this->arResult['FIELDS'] as $code => $field) {
            $props[$code] = $field["VALUE"];
        }

        $arLoadProductArray = [
            "IBLOCK_ID" => $this->arParams["IBLOCK_ID"],
            "NAME" => Loc::getMessage('DX_BF_NEW_ITEM_TITLE') . $this->arParams['TITLE'],
            "PROPERTY_VALUES" => $props,
        ];

        if ($el->Add($arLoadProductArray)) {
            $this->SentMessage($props);
            return [
                'SUCCESS' => true,
                'MESSAGE' => $this->arParams['SUCCESS_TEXT']
            ];
        } else {
            global $strError;
            return [
                'SUCCESS' => false,
                'MESSAGE' => $strError
            ];
        }
    }

    private function SentMessage($fields)
    {
        if (!empty($this->arParams["EMAIL_EVENT"])) {
            CEvent::Send($this->arParams["EMAIL_EVENT"], SITE_ID, $fields);
        }
    }

    private function checkCaptcha()
    {
        if ($this->arParams['GOOGLE_CAPTCHA'] != 'Y') {
            return true;
        }

        if (!empty($_REQUEST['token'])) {
            $url_google_api = 'https://www.google.com/recaptcha/api/siteverify';
            $query = $url_google_api . '?secret=' . $this->arParams['CAPTCHA_SECRET'] . '&response=' . $_REQUEST['token'] . '&remoteip=' . $_SERVER['REMOTE_ADDR'];
            $data = json_decode(file_get_contents($query));

            if ($data->success && $data->score > 0.2 && $data->action == 'backFormSent') {
                return true;
            }
        }

        $this->arResult["RESULT"] = [
            'SUCCESS' => false,
            'MESSAGE' => Loc::getMessage('DX_BF_CAPTCHA_ERROR')
        ];
        return false;
    }

    private function getFormFields()
    {
        $obCache = new CPHPCache();
        $cacheId = 'DevelopxBackformFieldsIblockId' . $this->arParams['IBLOCK_ID'];
        if ($obCache->InitCache($this->arParams["CACHE_TIME"], $cacheId, '/')) {
            $fields = $obCache->GetVars();
        } elseif ($obCache->StartDataCache()) {
            $properties = CIBlockProperty::GetList(
                ['SORT' => 'ASC'],
                ['ACTIVE' => 'Y', 'IBLOCK_ID' => $this->arParams['IBLOCK_ID']]
            );
            while ($propFields = $properties->Fetch()) {
                $fields[$propFields['CODE']] = [
                    'NAME' => $propFields['NAME'],
                    'TYPE' => $propFields['USER_TYPE'] == 'HTML' ? 'HTML' : $propFields['PROPERTY_TYPE'],
                    'IS_REQUIRED' => $propFields['IS_REQUIRED'],
                ];
            }
            $obCache->EndDataCache($fields);
        }
        return $fields;
    }

    public function checkFieldsResult()
    {
        $checked = true;
        foreach ($this->arResult['FIELDS'] as $code => $field) {
            if ($field['IS_REQUIRED'] == 'Y' && empty($this->arRequest['RESULT'][$code])) {
                $checked = false;
                $this->arResult['FIELDS'][$code]['ERROR'] = 'Y';
            }
            $this->arResult['FIELDS'][$code]['VALUE'] = $this->arRequest['RESULT'][$code];
        }
        return $checked;
    }

    private function checkUserDefaults($fields, $user)
    {
        $arUserFileds = [
            'NAME',
            'LAST_NAME',
            'EMAIL',
            'PERSONAL_PHONE'
        ];
        foreach ($arUserFileds as $userFiled) {
            if (!empty($fields[$userFiled]) && empty($fields[$userFiled]['VALUE']) && !empty($user[$userFiled])) {
                $fields[$userFiled]['VALUE'] = $user[$userFiled];
            }
        }
        return $fields;
    }

    public function executeComponent()
    {
        if (empty($this->arParams['IBLOCK_ID'])) {
            return;
        }

        $this->arResult['FIELDS'] = $this->getFormFields($this->arParams['IBLOCK_ID']);
        if ($this->arResult['USER'] = $this->getUser()) {
            $this->arResult['FIELDS'] = $this->checkUserDefaults($this->arResult['FIELDS'], $this->arResult['USER']);
        }

        $this->arRequest = $this->getRequest();
        if ($this->arRequest["AJAX_CALL"] == "Y" &&
            $this->arParams['AJAX_ID'] == $this->arRequest['bxajaxid'] &&
            $this->checkFieldsResult() &&
            $this->checkCaptcha()
        ) {
            $this->arResult["RESULT"] = $this->saveFormOrder();
        }

        $this->includeComponentTemplate();
    }
}