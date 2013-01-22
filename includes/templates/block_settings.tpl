{extends file='index.html'}
{block name=content}
<!-- upcoming settings page -->
<ul class="acSettings">
<li class="acSettingsListItem clearfix uiListItem uiListLight uiListVerticalItemBorder">
	<a class="pvm phs acSettingsListLink clearfix" rel="async" ajaxify="/ajax/settings/account/username.php" href="/settings?tab=account&section=username">
	<span class="pls acSettingsListItemLabel">
		<strong>Username</strong>
		</span>
		<span class="uiIconText acSettingsListItemEdit" style="padding-left: 23px;"></span>
		<span class="acSettingsListItemSaved hidden_elem">Changes saved</span>
		<span class="acSettingsListItemContent fcg"></span>
	</a>
	<div class="content"></div>
	</li>
</ul>
{/block}