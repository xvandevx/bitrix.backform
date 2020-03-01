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
    const EVENT_NAME = "DEVELOPX_NEW_FEEDBACK";
    const CAPTCHA_MODULE_NAME = 'developx.gcaptcha';

    public function onPrepareComponentParams($arParams)
    {
        if ($arParams['INCLUDE_GOOGLE_CAPTCHA'] == 'Y' &&
            !Loader::includeModule(self::CAPTCHA_MODULE_NAME)
        ) {
            $arParams['INCLUDE_GOOGLE_CAPTCHA'] = 'N';
        }

        if (!isset($arParams["CACHE_TIME"])) {
            $arParams["CACHE_TIME"] = self::DEFAULT_CACHE_TIME;
        }

        $arParams['AJAX_OPTION_JUMP'] = 'N';
        $arParams['AJAX_OPTION_HISTORY'] = 'N';
        return $arParams;
    }

    /**
     * @return array
     **/
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

    /**
     * @param array $fields
     * @param array $user
     * @return array
     */
    private function checkUserDefaults($fields, $user)
    {
        $arUserFields = [
            'NAME',
            'LAST_NAME',
            'EMAIL',
            'PERSONAL_PHONE'
        ];
        foreach ($arUserFields as $userFiled) {
            if (!empty($fields[$userFiled]) && empty($fields[$userFiled]['VALUE']) && !empty($user[$userFiled])) {
                $fields[$userFiled]['VALUE'] = $user[$userFiled];
            }
        }
        return $fields;
    }

    /**
     * @return array
     */
    private function getRequest()
    {
        return Context::getCurrent()->getRequest();
    }

    /**
     * @return boolean
     */
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

    /**
     * @return boolean
     */
    private function checkCaptcha()
    {
        if ($this->arParams['INCLUDE_GOOGLE_CAPTCHA'] != 'Y') {
            return true;
        }
        $captchaObj = new Developx\Gcaptcha\Main();
        if ($captchaObj->checkCaptcha()) {
            return true;
        } else {
            $this->arResult["RESULT"] = [
                'SUCCESS' => false,
                'MESSAGE' => Loc::getMessage('DX_CMT_CAPTCHA_ERROR')
            ];
            return false;
        }
    }

    /**
     * @return array
     */
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

        if ($id = $el->Add($arLoadProductArray)) {
            $this->SentMessage($id);
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

    private function SentMessage($id)
    {
        $formData = '';
        foreach ($this->arResult['FIELDS'] as $field) {
            $formData .= $field['NAME'] . ': ' . $field['VALUE'] . '<br>';
        }
        CEvent::Send(self::EVENT_NAME, SITE_ID, [
            'ID' => $id,
            'USER_ID' => $this->arResult['USER']['ID'],
            'FORM_DATA' => $formData,
            'IBLOCK_ID' => $this->arParams["IBLOCK_ID"],
            'IBLOCK_TYPE' => $this->arParams["IBLOCK_TYPE"],
        ]);
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