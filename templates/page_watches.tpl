{* $Header: /cvsroot/bitweaver/_bit_wiki/templates/page_watches.tpl,v 1.1 2006/09/03 08:40:01 jht001 Exp $ *}
{strip}
<div class="admin wiki">
	<div class="header">
		<h1>{tr}Watches{/tr} {tr}of{/tr} <a href="{$gContent->mInfo.display_url}">{$gContent->mInfo.title|escape}</a></h1>
	</div>

	<div class="body">

	{if $watches}
		<table border=1>
		<tr><th>User</th>
		<th>Email</th>
		</tr>
		{foreach item=watch from=$watches}
			<tr><td>{$watch.login}</td>
			<td>{$watch.email}</td>
			</tr>
		{/foreach}
		</table>
	{else}
		{tr}No Watches found for this page.{/tr}
	{/if}

	</div><!-- end .body -->
</div> <!-- end .wiki -->
{/strip}
