{* $Header: /cvsroot/bitweaver/_bit_wiki/modules/Attic/mod_breadcrumb.tpl,v 1.1.1.1.2.2 2005/08/05 23:01:00 squareing Exp $ *}
{strip}
{if $gBitSystem->isFeatureActive( 'feature_featuredLinks' )}
	{bitmodule title="$moduleTitle" name="breadcrumb"}
		<ol class="wiki">
			{section name=ix loop=$breadCrumb}
				<li>
					<a href="{$smarty.const.WIKI_PKG_URL}index.php?page={$breadCrumb[ix]}">
						{if $maxlen > 0}
							{$breadCrumb[ix]|truncate:$maxlen:"...":true}
						{else}
							{$breadCrumb[ix]}
						{/if}
					</a>
				</li>
			{sectionelse}
				<li></li>
			{/section}
		</ul>
	{/bitmodule}
{/if}
{/strip}
