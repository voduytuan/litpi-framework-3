{if count($notifySuccess) > 0}
<div class="notify-bar notify-bar-success">
	<div class="notify-bar-button{if $hidenotifyclose} hide{/if}"><a href="javascript:void(0);" onclick="javascript:$(this).parent().parent().fadeOut();" title="close"><img src="{$imageDir}notify/close-btn.png" border="0" alt="close" /></a></div>
	<div class="notify-bar-text">
		{if $notifySuccess|@is_array}
			<ul>
			{foreach item=notifySuccessItem from=$notifySuccess name="notifysuccess"}
				<li>{$notifySuccessItem}</li>
			{/foreach}
			</ul>
		{else}
			<p>{$notifySuccess}</p>
		{/if}
	</div>
</div>
{/if}

{if count($notifyError) > 0}
<div class="notify-bar notify-bar-error">
	<div class="notify-bar-button{if $hidenotifyclose} hide{/if}"><a href="javascript:void(0);" onclick="javascript:$(this).parent().parent().fadeOut();" title="close"><img src="{$imageDir}notify/close-btn.png" border="0" alt="close" /></a></div>
	<div class="notify-bar-text">
		{if $notifyError|@is_array}
			<ul>
			{foreach item=notifyErrorItem from=$notifyError name="notifyerror"}
				<li>{$notifyErrorItem}</li>
			{/foreach}
			</ul>
		{else}
			<p>{$notifyError}</p>
		{/if}
	</div>
</div>
{/if}

{if count($notifyWarning) > 0}
<div class="notify-bar notify-bar-warning">
	<div class="notify-bar-button{if $hidenotifyclose} hide{/if}"><a href="javascript:void(0);" onclick="javascript:$(this).parent().parent().fadeOut();" title="close"><img src="{$imageDir}notify/close-btn.png" border="0" alt="close" /></a></div>
	<div class="notify-bar-text">
		{if $notifyWarning|@is_array}
			<ul>
			{foreach item=notifyWarningItem from=$notifyWarning name="notifywarning"}
				<li>{$notifyWarningItem}</li>
			{/foreach}
			</ul>
		{else}
			<p>{$notifyWarning}</p>
		{/if}
	</div>
</div>
{/if}

{if count($notifyInformation) > 0}
<div class="notify-bar notify-bar-info">
	<div class="notify-bar-button{if $hidenotifyclose} hide{/if}"><a href="javascript:void(0);" onclick="javascript:$(this).parent().parent().fadeOut();" title="close"><img src="{$imageDir}notify/close-btn.png" border="0" alt="close" /></a></div>
	<div class="notify-bar-text">
		{if $notifyInformation|@is_array}
			<ul>
			{foreach item=notifyInformationItem from=$notifyInformation name="notifyinformation"}
				<li>{$notifyInformationItem}</li>
			{/foreach}
			</ul>
		{else}
			<p>{$notifyInformation}</p>
		{/if}
	</div>
</div>
{/if}