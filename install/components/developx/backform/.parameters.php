<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if (!CModule::IncludeModule("iblock"))
    return;

global $APPLICATION;

$arIBlockType = CIBlockParameters::GetIBlockTypes();
$rsIBlock = CIBlock::GetList(array(
    "sort" => "asc",
), array(
    "TYPE" => $arCurrentValues["IBLOCK_TYPE"],
    "ACTIVE" => "Y",
));
while ($arr = $rsIBlock->Fetch()) {
    $arIBlock[$arr["ID"]] = "[" . $arr["ID"] . "] " . $arr["NAME"];
}

$arFilter = array(
    "LID" => "ru"
);
$rsET = CEventType::GetList($arFilter);
while ($arET = $rsET->Fetch()) {

    $arEvents[$arET["EVENT_NAME"]] = "[" . $arET["EVENT_NAME"] . "] " . $arET["NAME"];
}

$arComponentParameters = array(
    "GROUPS" => array(
        "FIELDS" => array(
            "NAME" => GetMessage("DX_BF_PARAMS_GROUPS_FIELDS"),
        ),
        "CAPTCHA" => array(
            "NAME" => GetMessage("DX_BF_PARAMS_GROUPS_CAPTCHA"),
        ),
        "ADDITIONALLY" => array(
            "NAME" => GetMessage("DX_BF_PARAMS_GROUPS_ADDITIONALLY"),
        ),
    ),
    "PARAMETERS" => array(
        "IBLOCK_TYPE" => array(
            "PARENT" => "DATA_SOURCE",
            "NAME" => GetMessage("DX_BF_PARAMS_ITEM_IBLOCK_TYPE"),
            "TYPE" => "LIST",
            "ADDITIONAL_VALUES" => "Y",
            "VALUES" => $arIBlockType,
            "REFRESH" => "Y",
        ),
        "IBLOCK_ID" => array(
            "PARENT" => "DATA_SOURCE",
            "NAME" => GetMessage("DX_BF_PARAMS_ITEM_IBLOCK_ID"),
            "TYPE" => "LIST",
            "ADDITIONAL_VALUES" => "Y",
            "VALUES" => $arIBlock,
        ),
        "EMAIL_EVENT" => array(
            "PARENT" => "DATA_SOURCE",
            "NAME" => GetMessage("DX_BF_PARAMS_ITEM_EMAIL_EVENT"),
            "TYPE" => "LIST",
            "ADDITIONAL_VALUES" => "Y",
            "VALUES" => $arEvents,
        ),
        "TITLE" => array(
            "PARENT" => "FIELDS",
            "NAME" => GetMessage("DX_BF_PARAMS_ITEM_TITLE"),
            "TYPE" => "STRING",
            "DEFAULT" => GetMessage("DX_BF_PARAMS_ITEM_TITLE_DEFAULT"),
        ),
        "SUCCESS_TEXT" => array(
            "PARENT" => "FIELDS",
            "NAME" => GetMessage("DX_BF_PARAMS_ITEM_SUCCESS_TEXT"),
            "TYPE" => "STRING",
            "DEFAULT" => GetMessage("DX_BF_PARAMS_ITEM_SUCCESS_TEXT_DEFAULT"),
        ),
        "SENT_BUTTON_TEXT" => array(
            "PARENT" => "FIELDS",
            "NAME" => GetMessage("DX_BF_PARAMS_ITEM_SENT_BUTTON_TEXT"),
            "TYPE" => "STRING",
            "DEFAULT" => GetMessage("DX_BF_PARAMS_ITEM_SENT_BUTTON_TEXT_DEFAULT"),
        ),
        "GOOGLE_CAPTCHA" => array(
            "PARENT" => "CAPTCHA",
            "NAME" => GetMessage("DX_BF_PARAMS_ITEM_GOOGLE_CAPTCHA"),
            "TYPE" => "CHECKBOX",
            "DEFAULT" => "N",
        ),
        "CAPTCHA_KEY" => array(
            "PARENT" => "CAPTCHA",
            "NAME" => GetMessage("DX_BF_PARAMS_ITEM_CAPTCHA_KEY"),
            "TYPE" => "STRING",
            "DEFAULT" => "",
        ),
        "CAPTCHA_SECRET" => array(
            "PARENT" => "CAPTCHA",
            "NAME" => GetMessage("DX_BF_PARAMS_ITEM_CAPTCHA_SECRET"),
            "TYPE" => "STRING",
            "DEFAULT" => "",
        ),
        "INCLUDE_JQUERY" => array(
            "PARENT" => "ADDITIONALLY",
            "NAME" => GetMessage("DX_BF_PARAMS_ITEM_INCLUDE_JQUERY"),
            "TYPE" => "CHECKBOX",
            "DEFAULT" => "Y",
        ),
        "CACHE_TIME" => array(
            "DEFAULT" => 36000000,
        ),
    ),
);
?>
