<div class="listing wiki">
	<div class="header">
		<h1>Pages like: <a href="{$gContent->mInfo.display_url}">{$gContent->mInfo.title|escape}</a></h1>
	</div>

	<div class="body">
		<ul>
			{section name=back loop=$likepages}
				<li class="{cycle values='odd,even'}"><a href="{$gContent->mInfo.display_url}">{$likepages[back]}</a></li>
			{sectionelse}
				<li class="norecords">No pages found</li>
			{/section}
		</ul>
	</div><!-- end .body -->
</div>
