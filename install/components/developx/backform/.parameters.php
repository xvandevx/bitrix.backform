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

$arComponentParameters = array(
    "GROUPS" => array(
        "FIELDS" => array(
            "NAME" => GetMessage("DX_BF_PRM_GROUPS_FIELDS"),
        ),
        "ADDITIONALLY" => array(
            "NAME" => GetMessage("DX_BF_PRM_GROUPS_ADDITIONALLY"),
        ),
    ),
    "PARAMETERS" => array(
        "IBLOCK_TYPE" => array(
            "PARENT" => "DATA_SOURCE",
            "NAME" => GetMessage("DX_BF_PRM_ITEM_IBLOCK_TYPE"),
            "TYPE" => "LIST",
            "ADDITIONAL_VALUES" => "Y",
            "VALUES" => $arIBlockType,
            "REFRESH" => "Y",
        ),
        "IBLOCK_ID" => array(
            "PARENT" => "DATA_SOURCE",
            "NAME" => GetMessage("DX_BF_PRM_ITEM_IBLOCK_ID"),
            "TYPE" => "LIST",
            "ADDITIONAL_VALUES" => "Y",
            "VALUES" => $arIBlock,
        ),
        "TITLE" => array(
            "PARENT" => "FIELDS",
            "NAME" => GetMessage("DX_BF_PRM_ITEM_TITLE"),
            "TYPE" => "STRING",
            "DEFAULT" => GetMessage("DX_BF_PRM_ITEM_TITLE_DEFAULT"),
        ),
        "SUCCESS_TEXT" => array(
            "PARENT" => "FIELDS",
            "NAME" => GetMessage("DX_BF_PRM_ITEM_SUCCESS_TEXT"),
            "TYPE" => "STRING",
            "DEFAULT" => GetMessage("DX_BF_PRM_ITEM_SUCCESS_TEXT_DEFAULT"),
        ),
        "SENT_BUTTON_TEXT" => array(
            "PARENT" => "FIELDS",
            "NAME" => GetMessage("DX_BF_PRM_ITEM_SENT_BUTTON_TEXT"),
            "TYPE" => "STRING",
            "DEFAULT" => GetMessage("DX_BF_PRM_ITEM_SENT_BUTTON_TEXT_DEFAULT"),
        ),
        "INCLUDE_GOOGLE_CAPTCHA" => array(
            "PARENT" => "ADDITIONALLY",
            "NAME" => GetMessage("DX_BF_PRM_ITEM_INCLUDE_GOOGLE_CAPTCHA"),
            "TYPE" => "CHECKBOX",
            "DEFAULT" => "Y",
        ),
        "INCLUDE_JQUERY" => array(
            "PARENT" => "ADDITIONALLY",
            "NAME" => GetMessage("DX_BF_PRM_ITEM_INCLUDE_JQUERY"),
            "TYPE" => "CHECKBOX",
            "DEFAULT" => "Y",
        ),
        "CACHE_TIME" => array(
            "DEFAULT" => 36000000,
        ),
        "AJAX_MODE" => array(
            "PARENT" => "ADDITIONALLY",
            "NAME" => GetMessage("DX_BF_PRM_ITEM_AJAX_MODE"),
            "TYPE" => "CHECKBOX",
            "DEFAULT" => "Y",
        )
    ),
);