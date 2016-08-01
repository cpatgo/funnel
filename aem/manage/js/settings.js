{include file="settings.general.js"}
{include file="settings.admin.js"}
{include file="settings.public.js"}
{include file="settings.local.js"}
{if !$__ishosted}
{include file="settings.mailsending.js"}
{/if}
{include file="settings.delivery.js"}
{* include file="settings.addons.js" *}

{literal}

{/literal}
