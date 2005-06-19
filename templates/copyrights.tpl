<div class="display copyrights">
	<div class="header">
		<h1>{tr}Copyrights{/tr}</h1>
	</div>

	<div class="body">
	{legend legend="Copyright settings for page `$pageInfo.title`"}
		{section name=i loop=$copyrights}
			{form}
				<div class="row">
					{formlabel label="Title" for="copyleft-title"}
					{forminput}
						<input size="40" type="text" name="copyrightTitle" id="copyleft-title" value="{$copyrights[i].title|escape}" />
						{formhelp note=""}
					{/forminput}
				</div>

				<div class="row">
					{formlabel label="Authors" for="copyleft-authors"}
					{forminput}
						<input size="40" type="text" name="copyrightAuthors" id="copyleft-authors" value="{$copyrights[i].authors|escape}" />
						{formhelp note=""}
					{/forminput}
				</div>
				
				<div class="row">
					{formlabel label="Year" for="copyleft-year"}
					{forminput}
						<input size="4" type="text" name="copyrightYear" id="copyleft-year" value="{$copyrights[i].year|escape}" />
						{formhelp note=""}
					{/forminput}
				</div>

				<div class="row submit">
					<input type="hidden" name="page_id" value="{$pageInfo.page_id}" />
					<input type="hidden" name="copyright_id" value="{$copyrights[i].copyright_id|escape}" />
					<input type="submit" name="editcopyright" value="{tr}edit{/tr}" />
					<a href="{$gBitLoc.WIKI_PKG_URL}copyrights.php?page_id={$pageInfo.page_id}&amp;action=up&amp;copyright_id={$copyrights[i].copyright_id}">{biticon ipackage=liberty iname="nav_up" iexplain="move up"}</a>
					<a href="{$gBitLoc.WIKI_PKG_URL}copyrights.php?page_id={$pageInfo.page_id}&amp;action=down&amp;copyright_id={$copyrights[i].copyright_id}">{biticon ipackage=liberty iname="nav_down" iexplain="move down"}</a>
					<a title="{tr}Delete this copyright{/tr}" href="{$gBitLoc.WIKI_PKG_URL}copyrights.php?page_id={$pageInfo.page_id}&amp;action=delete&amp;copyright_id={$copyrights[i].copyright_id}" 
						onclick="return confirm('{tr}Are you sure you want to delete this copyright?{/tr}')">{biticon ipackage=liberty iname="delete" iexplain="delete"}</a>
				</div>
			{/form}
		{/section}
		{/legend}

		{form legend="Add a new copyright setting gor `$pageInfo.title`"}
			<input type="hidden" name="page_id" value="{$pageInfo.page_id}" />
			<div class="row">
				{formlabel label="Title" for="copyleft-tit"}
				{forminput}
					<input size="40" type="text" name="copyrightTitle" id="copyleft-tit" />
					{formhelp note=""}
				{/forminput}
			</div>

			<div class="row">
				{formlabel label="Authors" for="copyleft-aut"}
				{forminput}
					<input size="40" type="text" name="copyrightAuthors" id="copyleft-aut" />
					{formhelp note=""}
				{/forminput}
			</div>
			
			<div class="row">
				{formlabel label="Year" for="copyleft-yyyy"}
				{forminput}
					<input size="4" type="text" name="copyrightYear" id="copyleft-yyyy" />
					{formhelp note=""}
				{/forminput}
			</div>

			<div class="row submit">
				<input type="submit" name="addcopyright" value="{tr}add{/tr}" />
			</div>
		{/form}
	</div>
</div>
