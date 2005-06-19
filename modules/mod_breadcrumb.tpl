{* $Header: /cvsroot/bitweaver/_bit_wiki/modules/Attic/mod_breadcrumb.tpl,v 1.1 2005/06/19 06:12:45 bitweaver Exp $ *}
{strip}
{if $gBitSystemPrefs.feature_featuredLinks eq 'y'}
	{bitmodule title="$moduleTitle" name="breadcrumb"}
		<ol class="wiki">
			{section name=ix loop=$breadCrumb}
				<li>
					<a href="{$gBitLoc.WIKI_PKG_URL}index.php?page={$breadCrumb[ix]}">
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
