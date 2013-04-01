<li {if $structure_tree.structure_id==$smarty.request.structure_id}class="highlight"{/if}>
	{if $numbering && $structure_tree.prefix}<span class="numbering">{$structure_tree.prefix}</span>{/if}
	<a href="{$structure_tree.display_url|default:"`$smarty.const.WIKI_PKG_URL`index.php?structure_id=`$structure_tree.structure_id`"}">{$structure_tree.title|escape}</a>
</li>
