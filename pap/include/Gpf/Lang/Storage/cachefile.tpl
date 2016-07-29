<?php
$l = new Gpf_Lang_Language('{$l->getCode()|escape:'quotes'}');
$l->setName('{$l->getName()|escape:'quotes'}');
$l->setEnglishName('{$l->getEnglishName()|escape:'quotes'}');
$l->setAuthor('{$l->getAuthor()|escape:'quotes'}');
$l->setVersion('{$l->getVersion()|escape:'quotes'}');
$l->setDateFormat('{$l->getDateFormat()|escape:'quotes'}');
$l->setTimeFormat('{$l->getTimeFormat()|escape:'quotes'}');
$l->setThousandsSeparator('{$l->getThousandsSeparator()|escape:'quotes'}');
$l->setDecimalSeparator('{$l->getDecimalSeparator()|escape:'quotes'}');
$l->setTranslationPercentage('{$l->getTranslationPercentage()|escape:'quotes'}');
$l->setTextDirection('{$l->getTextDirection()|escape:'quotes'}');
$l->setDictionary(array(
{foreach from=$l->getDictionary() key=k item=v}
'{$k|escape:'quotes'}'=>'{$v|escape:'quotes'}',
{/foreach}
));
return $l;
