{* $Header: /cvsroot/bitweaver/_bit_wiki/modules/Attic/mod_breadcrumb.tpl,v 1.4 2005/10/12 15:14:13 spiderr Exp $ *}
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
		</ol>
	{/bitmodule}
{/if}
{/strip}
