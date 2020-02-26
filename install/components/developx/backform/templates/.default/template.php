<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? $this->setFrameMode(true); ?>
<?
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);
?>
<div class="back-form-overflow overflowJs"></div>
<div class="back-form">
    <div class="back-form__close backFormCloseJs">
        <svg enable-background="new 0 0 256 256" height="256px" version="1.1" viewBox="0 0 256 256" width="256px"
                 xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
            <path d="M137.051,128l75.475-75.475c2.5-2.5,2.5-6.551,0-9.051s-6.551-2.5-9.051,0L128,118.949L52.525,43.475  c-2.5-2.5-6.551-2.5-9.051,0s-2.5,6.551,0,9.051L118.949,128l-75.475,75.475c-2.5,2.5-2.5,6.551,0,9.051  c1.25,1.25,2.888,1.875,4.525,1.875s3.275-0.625,4.525-1.875L128,137.051l75.475,75.475c1.25,1.25,2.888,1.875,4.525,1.875  s3.275-0.625,4.525-1.875c2.5-2.5,2.5-6.551,0-9.051L137.051,128z"></path>
        </svg>
    </div>
    <div class="back-form__content loadJs">
        <? if (!empty($arResult["RESULT"])) { ?>
            <div class="back-form__form_text">
                <?= $arResult["RESULT"]["MESSAGE"]; ?>
            </div>
            <div class="back-form__btn backFormCloseJs"><?= Loc::getMessage('DX_BF_CLOSE') ?></div>
        <? } else { ?>
            <form class="back-form__form captchaFormJs" data-goal="make_order" action="<?= $arParams["PAGE_URL"] ?>"
                  method="post">
                <div class="back-form__title">
                    <?= $arParams['TITLE'] ?>
                </div>
                <? foreach ($arResult["FIELDS"] as $code => $field) { ?>
                    <span class="back-form__group <? if (isset($field['ERROR'])) { ?>back-form__error<? } ?> <? if (!empty($field['VALUE'])) { ?>not-empty<? } ?>">
                        <? if ($field["TYPE"] == "S") { ?>
                            <input type="text" name="RESULT[<?= $code ?>]" value="<?= $field["VALUE"] ?>"
                                   <? if ($field["IS_REQUIRED"] == "Y"){ ?>required<? } ?>>
                        <? } elseif ($field["TYPE"] == "HTML") { ?>
                            <textarea type="textarea" name="RESULT[<?= $code ?>]"
                                      <? if ($field["IS_REQUIRED"] == "Y"){ ?>required<? } ?>><?= $field["VALUE"] ?></textarea>
                        <? } ?>
                        <span class="back-form__highlight"></span>
                        <span class="back-form__bar"></span>
                        <label>
                            <?= $field["NAME"] ?> <? if ($field['IS_REQUIRED'] == 'Y') { ?>*<? } ?>
                        </label>
                        <span class="back-form__error-text"><?= Loc::getMessage('DX_BF_EMPTY_FIELD') ?></span>
                    </span>
                <? } ?>
                <div class="back-form__polit">
                    <?= Loc::getMessage('DX_BF_POLIT') ?>
                </div>
                <input type="submit" value="<?= $arParams['SENT_BUTTON_TEXT'] ?>" class="back-form__btn">
            </form>
        <? } ?>
    </div>
</div>

<div class="back-form-fixed-btn backFormShowJs">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 350 350">
        <path d="M335 80h-75V25c0-8.3-6.7-15-15-15H15C6.7 10 0 16.7 0 25v176.3c0 8.3 6.7 15 15 15h21V255c0 5.6 3.1 10.7 8.1 13.3 2.2 1.1 4.6 1.7 6.9 1.7 3 0 6-0.9 8.6-2.7L90 246v25.3c0 8.3 6.7 15 15 15h102.6l72.8 51c2.6 1.8 5.6 2.7 8.6 2.7 2.4 0 4.7-0.6 6.9-1.7C300.9 335.7 304 330.6 304 325v-38.7h31c8.3 0 15-6.7 15-15V95C350 86.7 343.3 80 335 80zM66 226.2v-24.9c0-8.3-6.7-15-15-15H30V40h200v40H105c-8.3 0-15 6.7-15 15v114.4L66 226.2zM320 256.3h-31c-8.3 0-15 6.7-15 15v24.9l-53.1-37.1c-2.5-1.8-5.5-2.7-8.6-2.7H120V110h200V256.3z"></path>
        <path d="M165 165c-3.9 0-7.8 1.6-10.6 4.4 -2.8 2.8-4.4 6.7-4.4 10.6 0 4 1.6 7.8 4.4 10.6 2.8 2.8 6.7 4.4 10.6 4.4s7.8-1.6 10.6-4.4c2.8-2.8 4.4-6.7 4.4-10.6 0-3.9-1.6-7.8-4.4-10.6C172.8 166.6 169 165 165 165z"></path>
        <path d="M220 165c-3.9 0-7.8 1.6-10.6 4.4 -2.8 2.8-4.4 6.7-4.4 10.6 0 4 1.6 7.8 4.4 10.6 2.8 2.8 6.7 4.4 10.6 4.4s7.8-1.6 10.6-4.4c2.8-2.8 4.4-6.7 4.4-10.6 0-3.9-1.6-7.8-4.4-10.6C227.8 166.6 224 165 220 165z"></path>
        <path d="M275 195c4 0 7.8-1.6 10.6-4.4 2.8-2.8 4.4-6.7 4.4-10.6 0-3.9-1.6-7.8-4.4-10.6C282.8 166.6 279 165 275 165s-7.8 1.6-10.6 4.4c-2.8 2.8-4.4 6.7-4.4 10.6 0 4 1.6 7.8 4.4 10.6C267.2 193.4 271.1 195 275 195z"></path>
    </svg>
</div>

<? if (empty($_REQUEST["AJAX_CALL"]) && $_REQUEST["AJAX_CALL"] != 'Y') {
    if ($arParams['INCLUDE_JQUERY'] == 'Y') {
        CJSCore::Init(["jquery"]);
    }
} ?>
<? if ($arParams['INCLUDE_GOOGLE_CAPTCHA'] == 'Y') { ?>
    <? $APPLICATION->IncludeComponent("developx:gcaptcha", ".default", array(), false); ?>
<? } ?>
