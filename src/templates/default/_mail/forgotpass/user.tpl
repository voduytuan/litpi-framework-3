{include file="`$smartyMail`header.tpl"}

<p><strong><big>Hi {$myUser->fullname},</big></strong></p>
<p>You received this email because you requested to recovery password at {$smarty.now|date_format}</p>
<p>Account Email: {$myUser->email}</p>

{if $activatedCode neq ''}
	<p>Click following link: <br /><br /><a href="{$conf.rooturl_admin}forgotpass/reset?email={$myUser->email}&amp;code={$activatedCode}">{$conf.rooturl_admin}forgotpass/reset?email={$myUser->email}&amp;code={$activatedCode}</a><br />...and type your new password to update.</p>
{/if}

<p></p>
<p>Thanks!<br />The Litpi Framework Team.</p>
{include file="`$smartyMail`footer.tpl"}