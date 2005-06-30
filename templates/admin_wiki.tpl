{strip}

{jstabs}
	{jstab title="Wiki Features"}
		{form legend="Wiki Features"}
			<input type="hidden" name="page" value="{$page}" />

			{foreach from=$formWikiFeatures key=item item=output}
				<div class="row">
					{formlabel label=`$output.label` for=$item}
					{forminput}
						{html_checkboxes name="$item" values="y" checked=`$gBitSystemPrefs.$item` labels=false id=$item}
						{formhelp note=`$output.note` page=`$output.page`}
					{/forminput}
				</div>
			{/foreach}

			<div class="row">
				{formlabel label="Global WikiPage Cache" for="wiki_cache"}
				{forminput}
					<select name="wiki_cache" id="wiki_cache">
						<option value="0" {if $wiki_cache eq 0}selected="selected"{/if}>{tr}0 (no cache){/tr}</option>
						<option value="60" {if $wiki_cache eq 60}selected="selected"{/if}>{tr}1 minute{/tr}</option>
						<option value="300" {if $wiki_cache eq 300}selected="selected"{/if}>{tr}5 minutes{/tr}</option>
						<option value="600" {if $wiki_cache eq 600}selected="selected"{/if}>{tr}10 minutes{/tr}</option>
						<option value="900" {if $wiki_cache eq 900}selected="selected"{/if}>{tr}15 minutes{/tr}</option>
						<option value="1800" {if $wiki_cache eq 1800}selected="selected"{/if}>{tr}30 minutes{/tr}</option>
						<option value="3600" {if $wiki_cache eq 3600}selected="selected"{/if}>{tr}1 hour{/tr}</option>
						<option value="7200" {if $wiki_cache eq 7200}selected="selected"{/if}>{tr}2 hours{/tr}</option>
					</select>
					{formhelp note="Cache wikipages for the given amount of time."}
				{/forminput}
			</div>

			<div class="row">
				{formlabel label="Warn on edit" for="feature_warn_on_edit"}
				{forminput}
					{html_checkboxes name="feature_warn_on_edit" values="y" checked=`$gBitSystemPrefs.feature_warn_on_edit` labels=false id="feature_warn_on_edit"}
				{/forminput}
				{forminput}
					<select name="warn_on_edit_time">
						<option value="1" {if $warn_on_edit_time eq 1}selected="selected"{/if}>{tr}1{/tr}</option>
						<option value="2" {if $warn_on_edit_time eq 2}selected="selected"{/if}>{tr}2{/tr}</option>
						<option value="5" {if $warn_on_edit_time eq 5}selected="selected"{/if}>{tr}5{/tr}</option>
						<option value="10" {if $warn_on_edit_time eq 10}selected="selected"{/if}>{tr}10{/tr}</option>
						<option value="15" {if $warn_on_edit_time eq 15}selected="selected"{/if}>{tr}15{/tr}</option>
						<option value="30" {if $warn_on_edit_time eq 30}selected="selected"{/if}>{tr}30{/tr}</option>
					</select> {tr}minutes{/tr}
					{formhelp note="Display a warning if someone has started editing a page within this time range and somebody else starts editing the same page."}
				{/forminput}
			</div>

			<div class="row">
				{formlabel label="Tables syntax" for="feature_wiki_tables"}
				{forminput}
					<select name="feature_wiki_tables" id="feature_wiki_tables">
						<option value="old" {if $gBitSystem->mPrefs.feature_wiki_tables eq 'old'}selected="selected"{/if}>{tr}|| for rows{/tr}</option>
						<option value="new" {if $gBitSystem->mPrefs.feature_wiki_tables eq 'new'}selected="selected"{/if}>{tr}\n for rows{/tr}</option>
					</select>
					{formhelp note="Either use || to start a new row in a table, or start a new line (recommended)."}
				{/forminput}
			</div>

			<div class="row submit">
				<input type="submit" name="wikifeatures" value="{tr}Change preferences{/tr}" />
			</div>
		{/form}
	{/jstab}

	{jstab title="Wiki Books"}
		{form legend="Wiki Book Settings"}
			<input type="hidden" name="page" value="{$page}" />

			{foreach from=$formWikiBooks key=item item=output}
				<div class="row">
					{formlabel label=`$output.label` for=$item}
					{forminput}
						{html_checkboxes name="$item" values="y" checked=`$gBitSystemPrefs.$item` labels=false id=$item}
						{formhelp note=`$output.note` page=`$output.page`}
					{/forminput}
				</div>
			{/foreach}
			<div class="row submit">
				<input type="submit" name="wikibooks" value="{tr}Change preferences{/tr}" />
			</div>
		{/form}
	{/jstab}

	{jstab title="In and Output"}
		{form legend="Wiki Input and Output Settings"}
			<input type="hidden" name="page" value="{$page}" />

			{foreach from=$formWikiInOut key=item item=output}
				<div class="row">
					{formlabel label=`$output.label` for=$item}
					{forminput}
						{html_checkboxes name="$item" values="y" checked=`$gBitSystemPrefs.$item` labels=false id=$item}
						{formhelp note=`$output.note` page=`$output.page`}
					{/forminput}
				</div>
			{/foreach}
			<div class="row submit">
				<input type="submit" name="wikiinout" value="{tr}Change preferences{/tr}" />
			</div>
		{/form}
	{/jstab}

	{jstab title="List Settings"}
		{form legend="List Settings"}
			<input type="hidden" name="page" value="{$page}" />

			{foreach from=$formWikiLists key=item item=output}
				<div class="row">
					{formlabel label=`$output.label` for=$item}
					{forminput}
						{html_checkboxes name="$item" values="y" checked=`$gBitSystemPrefs.$item` labels=false id=$item}
						{formhelp note=`$output.note` page=`$output.page`}
					{/forminput}
				</div>
			{/foreach}

			<div class="row submit">
				<input type="submit" name="wikilistconf" value="{tr}Change preferences{/tr}" />
			</div>
		{/form}
	{/jstab}

	{jstab title="Wiki Settings"}
		{form}
			{legend legend="Wiki Home Page"}
				<input type="hidden" name="page" value="{$page}" />
				<div class="row">
					{formlabel label="Wiki Home Page" for="wikiHomePage"}
					{forminput}
						<input type="text" name="wikiHomePage" id="wikiHomePage" size="25" value="{$wikiHomePage|escape}" />
						{formhelp note="When the wiki is accessed, this is the page that will be displayed as the first page."}
					{/forminput}
				</div>

				<div class="row submit">
					<input type="submit" name="setwikihome" value="{tr}Change preferences{/tr}" />
				</div>
			{/legend}

			{legend legend="Dumps and Export"}
				<div class="row">
					{forminput}
						<a href="{$gBitLoc.KERNEL_PKG_URL}admin/index.php?page=wiki&amp;dump=1">{tr}Generate dump{/tr}</a><br />
						<a href="{$gBitLoc.BIT_ROOT_URL}dump/{$bitdomain}new.tar">{tr}Download last dump{/tr}</a><br />
						<a href="{$gBitLoc.WIKI_PKG_URL}export_wiki_pages.php">{tr}Export wiki pages{/tr}</a>
					{/forminput}
				</div>
			{/legend}

			{legend legend="Wiki Tags"}
				<div class="row">
					{formlabel label="Create a tag" for="tagname"}
					{forminput}
						<input maxlength="20" size="20" type="text" name="tagname" id="tagname" /> 
						<input type="submit" name="createtag" value="{tr}create{/tr}" />
						{formhelp note="Creating a wiki tag sets sort of 'bookmark' that can be used to restore the wiki to a specific state."}
					{/forminput}
				</div>

				<div class="row">
					{formlabel label="Remove a Tag" for="remtagname"}
					{forminput}
						<select name="remtagname" id="remtagname">
							{section name=sel loop=$tags}
								<option value="{$tags[sel]|escape}">{$tags[sel]}</option>
							{sectionelse}
								<option>{tr}No records found{/tr}</option>
							{/section}
						</select> 
						<input type="submit" name="removetag" value="{tr}remove{/tr}" />
						{formhelp note="Here you can remove any obsolete wiki tags. This will <strong>not</strong> harm your wiki pages in any way."}
					{/forminput}
				</div>

				<div class="row">
					{formlabel label="Restore the wiki" for="restagname"}
					{forminput}
						<select name="restagname" id="restagname">
							{section name=sel loop=$tags}
								<option value="{$tags[sel]|escape}">{$tags[sel]}</option>
							{sectionelse}
								<option>{tr}No records found{/tr}</option>
							{/section}
						</select> 
						<input type="submit" name="restoretag" value="{tr}restore{/tr}" />
						{formhelp note="This will restore the entire wiki to when you created this tag. All the entries since then will be lost."}
					{/forminput}
				</div>
			{/legend}

			{legend legend="Wiki comments settings"}
				<div class="row">
					{formlabel label="Default number of comments per page" for="wiki_comments_per_page"}
					{forminput}
						<input size="5" type="text" name="wiki_comments_per_page" id="wiki_comments_per_page" value="{$wiki_comments_per_page|escape}" />
					{/forminput}
				</div>

				<div class="row">
					{formlabel label="Comments default ordering" for="wiki_comments_default_ordering"}
					{forminput}
						<select name="wiki_comments_default_ordering" id="wiki_comments_default_ordering">
							<option value="commentDate_desc" {if $wiki_comments_default_ordering eq 'commentDate_desc'}selected="selected"{/if}>{tr}Newest first{/tr}</option>
							<option value="commentDate_asc" {if $wiki_comments_default_ordering eq 'commentDate_asc'}selected="selected"{/if}>{tr}Oldest first{/tr}</option>
							<option value="points_desc" {if $wiki_comments_default_ordering eq 'points_desc'}selected="selected"{/if}>{tr}Points{/tr}</option>
						</select>
					{/forminput}
				</div>

				<div class="row">
					{formlabel label="Comments default display mode" for="wiki_comments_default_display_mode"}
					{forminput}
						<select name="wiki_comments_default_display_mode" id="wiki_comments_default_display_mode">
							<option value="threaded" {if $wiki_comments_default_display_mode eq 'threaded'}selected="selected"{/if}>{tr}Threaded{/tr}</option>
							<option value="flat" {if $wiki_comments_default_display_mode eq 'flat'}selected="selected"{/if}>{tr}Flat{/tr}</option>
						</select>
					{/forminput}
				</div>

				<div class="row submit">
					<input type="submit" name="wikiprefs" value="{tr}Change preferences{/tr}" />
				</div>
			{/legend}

			{legend legend="Wiki Link Format"}
				<div class="row">
					{formlabel label="Wiki Link Format" for="wiki_page_regex"}
					{forminput}
						<select name="wiki_page_regex" id="wiki_page_regex">
							<option value="complete" {if $wiki_page_regex eq 'complete'}selected="selected"{/if}>{tr}complete{/tr}</option>
							<option value="full" {if $wiki_page_regex eq 'full'}selected="selected"{/if}>{tr}latin{/tr}</option>
							<option value="strict" {if $wiki_page_regex eq 'strict'}selected="selected"{/if}>{tr}english{/tr}</option>
						</select>
						{formhelp note="Controls recognition of Wiki links using the two parenthesis Wiki link syntax <i>((page name))</i>."}
					{/forminput}
				</div>

				<div class="row submit">
					<input type="submit" name="setwikiregex" value="{tr}Change preferences{/tr}" />
				</div>
			{/legend}

			{legend legend="Wiki History"}
				<div class="row">
					{formlabel label="Maximum number of versions in history" for="maxVersions"}
					{forminput}
						<input size="5" type="text" name="maxVersions" id="maxVersions" value="{$maxVersions|escape}" />
					{/forminput}
				</div>

				<div class="row">
					{formlabel label="Never delete versions younger than days" for="keep_versions"}
					{forminput}
						<input size="5" type="text" name="keep_versions" id="keep_versions" value="{$keep_versions|escape}" />
					{/forminput}
				</div>

				<div class="row submit">
					<input type="submit" name="wikisetprefs" value="{tr}Change preferences{/tr}" />
				</div>
			{/legend}

			{legend legend="Copyright Management"}
				<div class="row">
					{formlabel label="Enable Feature" for="wiki_feature_copyrights"}
					{forminput}
						{html_checkboxes name="wiki_feature_copyrights" values="y" checked=$wiki_feature_copyrights labels=false id="wiki_feature_copyrights"}
					{/forminput}
				</div>

				<div class="row">
					{formlabel label="License Page" for="wikiLicensePage"}
					{forminput}
						<input type="text" name="wikiLicensePage" id="wikiLicensePage" value="{$wikiLicensePage|escape}" />
					{/forminput}
				</div>

				<div class="row">
					{formlabel label="Submit Notice" for="wikiSubmitNotice"}
					{forminput}
						<input type="text" name="wikiSubmitNotice" id="wikiSubmitNotice" value="{$wikiSubmitNotice|escape}" />
					{/forminput}
				</div>

				<div class="row submit">
					<input type="submit" name="wikisetcopyright" value="{tr}Change preferences{/tr}" />
				</div>
			{/legend}

			{legend legend="Wiki Watch"}
				{formfeedback warning="This feature has been disabled for now since it's not functional."}

				{foreach from=$formWikiWatch key=item item=output}
					<div class="row">
						{formlabel label=`$output.label` for=$item}
						{forminput}
							{html_checkboxes name="$item" values="y" checked=`$gBitSystemPrefs.$item` labels=false id=$item}
							{formhelp note=`$output.note` page=`$output.page`}
						{/forminput}
					</div>
				{/foreach}

				<div class="row submit">
					<input type="submit" name="wikiwatch" value="{tr}Change preferences{/tr}" />
				</div>
			{/legend}
		{/form}
	{/jstab}
{/jstabs}
{/strip}

{if $gBitSystem->mPrefs.package_tiki_forums eq 'y'}
			<div class="boxcontent">
				<form method="post" action="{$gBitLoc.KERNEL_PKG_URL}admin/index.php?page=wiki">
				<table class="panel"><tr>
					<th colspan="2">{tr}Wiki Discussion{/tr}</th>
				</tr><tr>
					<td width="70%"><label for="feature_wiki_discuss">{tr}Discuss pages on forums{/tr}:</label> </td>
					<td width="30%"><input type="checkbox" name="feature_wiki_discuss" id="feature_wiki_discuss" {if $gBitSystem->mPrefs.feature_wiki_discuss eq 'y'}checked="checked"{/if} /></td>
				</tr><tr>
					<td>{tr}Forum{/tr}:</td>
					<td>
						<select name="wiki_forum">
							{section name=ix loop=$all_forums}
								<option value="{$all_forums[ix].name|escape}" {if $all_forums[ix].name eq $wiki_forum}selected="selected"{/if}>{$all_forums[ix].name}</option>
							{sectionelse}
								<option>{tr}No records found{/tr}</option>
							{/section}
						</select>
					</td>
				</tr><tr class="panelsubmitrow">
					<td colspan="2"><input type="submit" name="wikidiscussprefs" value="{tr}Change preferences{/tr}" /></td>
				</tr></table>
				</form>
			</div>
{/if}
