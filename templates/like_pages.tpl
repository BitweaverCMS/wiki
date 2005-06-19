<div class="display wiki">
	<div class="header">
		<h1>{tr}Pages like{/tr} <a href="{$pageInfo.display_url}">{$pageInfo.title}</a></h1>
	</div>

	<div class="body">
		{section name=back loop=$likepages}
			<a href="{$pageInfo.display_url}">{$likepages[back]}</a><br />
		{sectionelse}
			<div class="norecords">{tr}No pages found{/tr}</div>
		{/section}
	</div>
</div>
