<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>
<div class="news-list">
    <?if($arParams["DISPLAY_TOP_PAGER"]):?>
        <?=$arResult["NAV_STRING"]?><br />
    <?endif;?>
    <?foreach($arResult["ITEMS"] as $arItem):?>
        <?
        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
        $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
        ?>
    <div class="news-item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
        <?if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arItem["PREVIEW_PICTURE"])):?>
                        <a  class="preview_picture_link" href="<?=$arItem["DETAIL_PAGE_URL"]?>"><img
                        class="preview_picture"
                        border="0"
                        src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>"
                        width="<?=$arItem["PREVIEW_PICTURE"]["WIDTH"]?>"
                        height="<?=$arItem["PREVIEW_PICTURE"]["HEIGHT"]?>"
                        alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>"
                        title="<?=$arItem["PREVIEW_PICTURE"]["TITLE"]?>"
                        style="float:left"
                    /></a>
        <?endif?>
        <div>
            <?php
            $APPLICATION->IncludeComponent(
                'altopromo:vote',
                'stars',
                array(
                    'IBLOCK_TYPE' => $arItem['IBLOCK_TYPE_ID'],
                    'IBLOCK_ID' => $arItem['IBLOCK_ID'],
                    'ELEMENT_ID' => $arItem['ID'],
                    'ELEMENT_CODE' => '',
                    'MAX_VOTE' => '5',
                    'VOTE_NAMES' => array('1'),
                    'SET_STATUS_404' => 'N',
                    'CACHE_TYPE' => $arParams['CACHE_TYPE'],
                    'CACHE_TIME' => $arParams['CACHE_TIME'],
                    'AJAX_MODE' => 'Y'
                ),
                $component,
                array('HIDE_ICONS' => 'Y')
            );
            ?>
        </div>

        <?if($arParams["DISPLAY_NAME"]!="N" && $arItem["NAME"]):?>
                        <a href="<?echo $arItem["DETAIL_PAGE_URL"]?>"><b><?echo $arItem["NAME"]?></b></a><br />
        <?endif;?>
        <?if($arParams["DISPLAY_PREVIEW_TEXT"]!="N" && $arItem["PREVIEW_TEXT"]):?>
            <?echo $arItem["PREVIEW_TEXT"];?>
        <?endif;?>
        </div>
    <?endforeach;?>
    <?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
        <br /><?=$arResult["NAV_STRING"]?>
    <?endif;?>

</div>
