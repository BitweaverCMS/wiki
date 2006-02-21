{* $Header: /cvsroot/bitweaver/_bit_wiki/templates/edit_page.tpl,v 1.23 2006/02/21 12:56:59 squareing Exp $ *}
{strip}
<div class="floaticon">{bithelp}</div>

<div class="edit wiki">
	<div class="header">
		<h1>
			{* this weird dual assign thing is cause smarty wont interpret backticks to object in assign tag - spiderr *}
			{assign var=conDescr value=$gContent->mType.content_description}
			{if $pageInfo.page_id}
				{assign var=editLabel value="{tr}Edit{/tr} $conDescr"}
				{tr}{tr}Edit{/tr} {$pageInfo.original_title}{/tr}
			{else}
				{assign var=editLabel value="{tr}Create{/tr} $conDescr"}
				{tr}{$editLabel}{/tr}
			{/if}
		</h1>
	</div>

	{* Check to see if there is an editing conflict *}
	{if $errors.edit_conflict}
		<script type="text/javascript">/* <![CDATA[ */
			alert( "{$errors.edit_conflict|strip_tags}" );
		/* ]]> */</script>
		{formfeedback warning=`$errors.edit_conflict`}
	{/if}

	<div class="body">
		{if $translateFrom}
			<div class="translate">
				{include file="bitpackage:wiki/page_header.tpl" pageInfo=$translateFrom->mInfo}
				{include file="bitpackage:wiki/page_display.tpl" pageInfo=$translateFrom->mInfo}
			</div>
		{/if}

		{if $preview}
			{if $pageInfo.edit_section == 1 }
					<h2>{tr}Preview Section {$pageInfo.section} of: {$title}{/tr}</h2>
			{else}
					<h2>{tr}Preview {$title}{/tr}</h2>
			{/if}
			<div class="preview">
				{include file="bitpackage:wiki/page_header.tpl"}
				{include file="bitpackage:wiki/page_display.tpl"}
			</div>
		{/if}

		{if $page eq 'SandBox'}
			<div class="admin box">{tr}The SandBox is a page where you can practice your editing skills, use the preview feature to preview the appeareance of the page, no versions are stored for this page.{/tr}</div>
		{/if}

		{form enctype="multipart/form-data" id="editpageform"}
			{jstabs}
				{jstab title="$editLabel Body"}
					{legend legend="`$editLabel` Body"}
						<input type="hidden" name="page_id" value="{$pageInfo.page_id}" />

						<div class="row">
							{formfeedback warning=`$errors.title`}
							{formlabel label="$conDescr Title" for="title"}
							{forminput}
								{if $gBitUser->hasPermission( 'bit_p_rename' ) || !$pageInfo.page_id}
									<input type="text" size="50" maxlength="200" name="title" id="title" value="{$pageInfo.title}" />
								{else}
									{$page} {$pageInfo.title}
								{/if}
							{/forminput}
						</div>

						{if $gBitSystem->isFeatureActive( 'wiki_templates' ) and $gBitUser->hasPermission( 'bit_p_use_content_templates' )}
							<div class="row">
								{formlabel label="Apply template" for="template_id"}
								{forminput}
									<select name="template_id" id="template_id" onchange="javascript:document.getElementById('editpageform').submit();">
										<option value="0">{tr}none{/tr}</option>
										{section name=ix loop=$templates}
											<option value="{$templates[ix].template_id|escape}">{tr}{$templates[ix].name}{/tr}</option>
										{/section}
									</select>
								{/forminput}
							</div>
						{/if}

						{if $gBitSystem->isFeatureActive( 'wiki_description' )}
							<div class="row">
								{formlabel label="Description" for="description"}
								{forminput}
									<input size="50" type="text" maxlength="200" name="description" id="description" value="{$pageInfo.description|escape:html}" />
									{formhelp note="Brief description of the page. This is visible when you hover over a link to this page and just below the title of the wiki page."}
								{/forminput}
							</div>
						{/if}

						{include file="bitpackage:liberty/edit_format.tpl"}

						{if $gBitSystem->isPackageActive( 'smileys' )}
							{include file="bitpackage:smileys/smileys_full.tpl"}
						{/if}

						{if $gBitSystem->isPackageActive( 'quicktags' )}
							{include file="bitpackage:quicktags/quicktags_full.tpl"}
						{/if}

						{if $pageInfo.edit_section == 1 }
							<input type="hidden" name="section" value="{$pageInfo.section}" />
						{/if}

						<div class="row">
							{formlabel label="Body" for=$textarea_id}
							{forminput}
								<textarea id="{$textarea_id}" name="edit" rows="{$smarty.cookies.rows|default:20}" cols="50">{$pageInfo.data|escape:html}</textarea>
							{/forminput}
						</div>

						{if $footnote}
							<div class="row">
								{formlabel label="Footnotes" for="footnote"}
								{forminput}
									<textarea name="footnote" id="footnote" rows="8" cols="50">{$footnote|escape}</textarea>
									{formhelp note=""}
								{/forminput}
							</div>
						{/if}

						{if $page ne 'SandBox'}
							<div class="row">
								{formlabel label="Comment" for="edit_comment"}
								{forminput}
									<input size="50" type="text" name="edit_comment" id="edit_comment" value="{$pageInfo.edit_comment}" />
									{formhelp note="Add a comment to illustrate your most recent changes."}
								{/forminput}
							</div>
						{/if}

						{if $gBitUser->hasPermission( 'bit_p_minor' )}
							<div class="row">
								{formlabel label="Minor save" for="isminor"}
								{forminput}
									<input type="checkbox" name="isminor" id="isminor" value="on" />
									{formhelp note="This will prevent the generation of a new version. You can use this, if your changes are minor."}
								{/forminput}
							</div>
						{/if}

						{include file="bitpackage:liberty/edit_services_inc.tpl serviceFile=content_edit_mini_tpl}

						<div class="row submit">
							<input type="submit" name="fCancel" value="{tr}Cancel{/tr}" />&nbsp;
							<input type="submit" name="preview" value="{tr}Preview{/tr}" />&nbsp;
							<input type="submit" name="fSavePage" value="{tr}Save{/tr}" />
						</div>

						{if $gBitSystem->isFeatureActive( 'wiki_attachments' )}
							{include file="bitpackage:liberty/edit_storage_list.tpl"}
						{/if}
					{/legend}
				{/jstab}

				{include file="bitpackage:liberty/edit_services_inc.tpl serviceFile=content_edit_tab_tpl}

				{if $gBitSystem->isFeatureActive( 'wiki_attachments' ) && $show_attachments eq 'y' && $gBitUser->hasPermission('bit_p_content_attachments')}
					{jstab title="Attachments"}
						{legend legend="Attachments"}
							{include file="bitpackage:liberty/edit_storage.tpl"}
						{/legend}
					{/jstab}
				{/if}

				{if $gBitSystem->isFeatureActive( 'wiki_icache' ) or $gBitSystem->isFeatureActive( 'wiki_spellcheck' )}
					{jstab title="Advanced"}
						{legend legend="Advanced Options"}
							{if $gBitSystem->isFeatureActive( 'wiki_spellcheck' )}
								<div class="row">
									{formlabel label="Spellcheck" for="spellcheck"}
									{forminput}
										<input type="checkbox" name="spellcheck" id="spellcheck" {if $spellcheck eq 'y'}checked="checked"{/if} />
										{formhelp note=""}
									{/forminput}
								</div>
							{/if}

							{if $gBitSystem->isFeatureActive( 'wiki_icache' )}
								<div class="row">
									{formlabel label="Cache" for="wiki_cache"}
									{forminput}
										<select name="wiki_cache" id="wiki_cache">
											<option value="0"    {if $gBitSystemPrefs.wiki_cache eq 0}selected="selected"{/if}>{tr}0 (no cache){/tr}</option>
											<option value="60"   {if $gBitSystemPrefs.wiki_cache eq 60}selected="selected"{/if}>{tr}1 minute{/tr}</option>
											<option value="300"  {if $gBitSystemPrefs.wiki_cache eq 300}selected="selected"{/if}>{tr}5 minutes{/tr}</option>
											<option value="600"  {if $gBitSystemPrefs.wiki_cache eq 600}selected="selected"{/if}>{tr}10 minutes{/tr}</option>
											<option value="900"  {if $gBitSystemPrefs.wiki_cache eq 900}selected="selected"{/if}>{tr}15 minutes{/tr}</option>
											<option value="1800" {if $gBitSystemPrefs.wiki_cache eq 1800}selected="selected"{/if}>{tr}30 minutes{/tr}</option>
											<option value="3600" {if $gBitSystemPrefs.wiki_cache eq 3600}selected="selected"{/if}>{tr}1 hour{/tr}</option>
											<option value="7200" {if $gBitSystemPrefs.wiki_cache eq 7200}selected="selected"{/if}>{tr}2 hours{/tr}</option>
										</select>
										{formhelp note=""}
									{/forminput}
								</div>
							{/if}
						{/legend}
					{/jstab}
				{/if}

				{if $gBitSystem->isFeatureActive( 'wiki_copyrights' )}
					{jstab title="Copyright"}
						<div class="row">
							{legend legend="Copyright Settings" for="copyrightTitle"}
								<div class="row">
									{formlabel label="Title" for="copyrightTitle"}
									{forminput}
										<input size="40" type="text" name="copyrightTitle" id="copyrightTitle" value="{$copyrightTitle|escape}" />
									{/forminput}
								</div>

								<div class="row">
									{formlabel label="Authors" for="copyrightAuthors"}
									{forminput}
										<input size="40" type="text" name="copyrightAuthors" id="copyrightAuthors" value="{$copyrightAuthors|escape}" />
									{/forminput}
								</div>

								<div class="row">
									{formlabel label="Year" for="copyrightYear"}
									{forminput}
										<input size="4" type="text" name="copyrightYear" id="copyrightYear" value="{$copyrightYear|escape}" />
									{/forminput}
								</div>

								<div class="row">
									{formlabel label="License"}
									{forminput}
										<a href="{$smarty.const.WIKI_PKG_URL}index.php?page={$wiki_license_page}">{tr}{$wiki_license_page}{/tr}</a>
										{formhelp note=""}
									{/forminput}
								</div>

								{if $wiki_submit_notice neq ""}
									<div class="row">
										{formlabel label="Important"}
										{forminput}
											{$wiki_submit_notice}
											{formhelp note=""}
										{/forminput}
									</div>
								{/if}
							{/legend}
						</div>
					{/jstab}
				{/if}

				{if $gBitSystem->isFeatureActive( 'wiki_url_import' )}
					{jstab title="Import HMTL"}
						{legend legend="Import HMTL"}
							<div class="row">
								{formlabel label="Import HTML from URL" for="suck_url"}
								{forminput}
									<input type="text" size="50" name="suck_url" id="suck_url" value="{$suck_url|escape}" />
									{formhelp note=""}
								{/forminput}
							</div>

							<div class="row">
								{formlabel label="Try to convert HTML to wiki" for="parsehtml"}
								{forminput}
									<input type="checkbox" name="parsehtml" id="parsehtml" {if $parsehtml eq 'y'}checked="checked"{/if} />
									{formhelp note=""}
								{/forminput}
							</div>

						{* SPIDERKILL - disable wiki Import
							{if $gBitUser->hasPermission( 'bit_p_admin_wiki' )}
								<tr><td>
									{tr}Import file{/tr}:</td><td>
									<input name="userfile1" type="file" />
									{ * <a href="{$smarty.const.WIKI_PKG_URL}export_wiki_pages.php?page_id={$pageInfo.page_id}&amp;all=1">{tr}export all versions{/tr}</a> * }
								</td></tr>
							{/if} { * end upload file row * }
						*}

							<div class="row submit">
								<input type="submit" name="do_suck" value="{tr}Import{/tr}" />
							</div>
						{/legend}
					{/jstab}
				{/if}
			{/jstabs}
		{/form}

		<br /><br />
		{include file="bitpackage:liberty/edit_help_inc.tpl"}

	</div><!-- end .body -->
</div><!-- end .admin -->
{/strip}
