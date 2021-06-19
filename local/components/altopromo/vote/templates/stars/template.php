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

if($arParams["DISPLAY_AS_RATING"] == "vote_avg")
{
	if($arResult["PROPERTIES"]["vote_count"]["VALUE"])
		$votesValue = round($arResult["PROPERTIES"]["vote_sum"]["VALUE"]/$arResult["PROPERTIES"]["vote_count"]["VALUE"], 2);
	else
		$votesValue = 0;
}
else
{
	$votesValue = $arResult["PROPERTIES"]["rating"]["VALUE"];
}
$votesValue = (float)$votesValue;

$votesCount = (int)$arResult["PROPERTIES"]["vote_count"]["VALUE"];

if (isset($arParams["AJAX_CALL"]) && $arParams["AJAX_CALL"]=="Y")
{
	$APPLICATION->RestartBuffer();
	header('Content-Type: application/json');
	echo \Bitrix\Main\Web\Json::encode(array(
		"value" => $votesValue,
		"votes" => $votesCount
	));
	return;
}

CJSCore::Init(array("ajax"));
$strObName = "";
$strObName = "bx_vo_".$arParams["IBLOCK_ID"]."_".$arParams["ELEMENT_ID"].'_'.$this->randString();
$arJSParams = array(
	"progressId" => $strObName."_progr",
	"ratingId" => $strObName."_rating",
	"starsId" => $strObName."_stars",
	"ajaxUrl" => $componentPath."/component.php",
	"checkVoteUrl" => $componentPath."/ajax.php",
	'ajaxParams' => $arResult["~AJAX_PARAMS"],
	'siteId' => SITE_ID,
	'voteData' => array(
		'element' => (int)$arResult["ID"],
		'percent' => ($votesCount > 0 ? $votesValue*20 : 0),
		'count' => $votesCount
	),
	'readOnly' => (isset($arParams['READ_ONLY']) && $arParams['READ_ONLY'] === 'Y')
);

?>

<div class="rating">
    <?php for($i = 0; $i != $arParams['MAX_VOTE']; $i++):
        $starClass = "";
        if($votesValue != 0 && $i <= $votesValue)
            $starClass = 'stary';
        ?>
        <span class="fa fa-stack">
            <i class="fa fa-star fa-stack-2x <?= $starClass ?>"></i>
        </span>
    <?php endfor;?>
</div>
<div class="iblock-vote">
    <form method="post" action="<?=POST_FORM_ACTION_URI?>">
        <?echo bitrix_sessid_post();?>
        <input type="hidden" name="back_page" value="<?=$arResult["BACK_PAGE_URL"]?>" />
        <input type="hidden" name="vote_id" value="<?=$arResult["ID"]?>" />
        <input type="hidden" name="rating" value="1" />
        <input type="submit" name="vote_m" value="-" />
        <input type="submit" name="vote" value="+" />

    </form>
</div>
<script type="text/javascript">
	var <?= $strObName ?> = new JCIblockVoteStars(<?=CUtil::PhpToJSObject($arJSParams, false, true, true);?>);
</script>
