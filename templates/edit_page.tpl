{strip}
<div class="floaticon">{bithelp}</div>
<div class="edit wiki">
	<div class="header">
		<h1>
			{* this weird dual assign thing is cause smarty wont interpret backticks to object in assign tag - spiderr *}
			{assign var=conDescr value=$gContent->getContentTypeName()}
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
		{formfeedback warning=$errors.edit_conflict}
	{/if}

	<div class="body">
		{if $errors} 
			{formfeedback warning='There were one or more errors trying to store the page. Please correct them and then try saving again.'}
		{/if}

		{if $translateFrom}
			<div class="translate">
				{include file="bitpackage:wiki/page_header.tpl" pageInfo=$translateFrom->mInfo}
				{include file="bitpackage:wiki/page_display.tpl" pageInfo=$translateFrom->mInfo}

				{if $translateFrom->mInfo.google_guess}
					<hr />
					<h1>{tr}Google's translation attempt{/tr}</h1>
					<code>{$translateFrom->mInfo.google_guess|nl2br}</code>
				{/if}
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
				{jstab title="Body"}
					{legend legend="`$editLabel` Body"}
						<input type="hidden" name="page_id" value="{$pageInfo.page_id}" />
						<input type="hidden" name="content_id" value="{$pageInfo.content_id}" />

						<div class="form-group">
							{formfeedback warning=$errors.title}
							{formlabel label="$conDescr Title"}
							{forminput}
								{if $gBitUser->hasPermission( 'p_wiki_rename_page' ) || !$pageInfo.page_id}
									<input type="text" class="form-control" maxlength="200" name="title" id="title" value="{$pageInfo.title|escape}" />
								{else}
									{$page} {$pageInfo.title|escape}
								{/if}
							{/forminput}
						</div>

						{if $gBitSystem->isFeatureActive( 'wiki_description' )}
							<div class="form-group">
								{formlabel label="Summary" for="summary"}
								{forminput}
									<input type="text" class="form-control" name="summary" id="summary" value="{$pageInfo.summary|escape:html}" />
									{formhelp note="Brief description of the page. This is visible when you hover over a link to this page and just below the title of the wiki page."}
								{/forminput}
							</div>
						{/if}

						{if $pageInfo.edit_section == 1}
							<input type="hidden" name="section" value="{$pageInfo.section}" />
						{/if}

						{textarea edit=$pageInfo.data}

						{if $footnote}
							<div class="form-group">
								{formlabel label="Footnotes" for="footnote"}
								{forminput}
									<textarea class="form-control" name="footnote" id="footnote" rows="8">{$footnote|escape}</textarea>
									{formhelp note=""}
								{/forminput}
							</div>
						{/if}

						{if $page ne 'SandBox'}
							<div class="form-group">
								{formlabel label="Comment" for="edit_comment"}
								{forminput}
									<input type="text" class="form-control" name="edit_comment" id="edit_comment" value="{$pageInfo.edit_comment}" />
									{formhelp note="Add a comment to illustrate your most recent changes."}
								{/forminput}
							</div>
						{/if}

						{if $gBitUser->hasPermission( 'p_wiki_save_minor' )}
							<div class="form-group">
								{forminput}
								<label class="checkbox">
									<input type="checkbox" name="isminor" id="isminor" value="on" {if $pageInfo.isminor}checked="checked" {/if}/>Minor save
									{formhelp note="This will prevent the generation of a new version. You can use this, if your changes are minor."}
								</label>
								{/forminput}
							</div>
						{/if}

						{include file="bitpackage:liberty/edit_content_alias_inc.tpl"}

						{include file="bitpackage:liberty/edit_services_inc.tpl" serviceFile="content_edit_mini_tpl"}

						{if $gBitSystem->isFeatureActive( 'wiki_attachments' )}
							{include file="bitpackage:liberty/edit_storage_list.tpl"}
						{/if}
					{/legend}
				{/jstab}

				{include file="bitpackage:liberty/edit_services_inc.tpl" serviceFile="content_edit_tab_tpl"}

				{if $gBitSystem->isFeatureActive( 'wiki_attachments' ) && $gBitUser->hasPermission('p_liberty_attach_attachments')}
					{jstab title="Attachments"}
						{legend legend="Attachments"}
							{include file="bitpackage:liberty/edit_storage.tpl"}
						{/legend}
					{/jstab}
				{/if}

				{if $gBitSystem->isFeatureActive( 'wiki_copyrights' )}
					{jstab title="Copyright"}
						<div class="form-group">
							{legend legend="Copyright Settings" for="copyrightTitle"}
								<div class="form-group">
									{formlabel label="Title" for="copyrightTitle"}
									{forminput}
										<input type="text" class="form-control" name="copyrightTitle" id="copyrightTitle" value="{$copyrightTitle|escape}" />
									{/forminput}
								</div>

								<div class="form-group">
									{formlabel label="Authors" for="copyrightAuthors"}
									{forminput}
										<input type="text" class="form-control" name="copyrightAuthors" id="copyrightAuthors" value="{$copyrightAuthors|escape}" />
									{/forminput}
								</div>

								<div class="form-group">
									{formlabel label="Year" for="copyrightYear"}
									{forminput}
										<input type="text" class="form-control" name="copyrightYear" id="copyrightYear" value="{$copyrightYear|escape}" />
									{/forminput}
								</div>

								<div class="form-group">
									{formlabel label="License"}
									{forminput}
										<a href="{$smarty.const.WIKI_PKG_URL}index.php?page={$wiki_license_page}">{tr}{$wiki_license_page}{/tr}</a>
										{formhelp note=""}
									{/forminput}
								</div>

								{if $wiki_submit_notice neq ""}
									<div class="form-group">
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
							<div class="form-group">
								{formlabel label="Import HTML from URL" for="suck_url"}
								{forminput}
									<input type="text" class="form-control" name="suck_url" id="suck_url" value="{$suck_url|escape}" />
									{formhelp note=""}
								{/forminput}
							</div>

							<div class="form-group">
								{forminput}
								<label class="checkbox">
									<input type="checkbox" name="parsehtml" id="parsehtml" {if $parsehtml eq 'y'}checked="checked"{/if} />Try to convert HTML to wiki
									{formhelp note=""}
								</label>
								{/forminput}
							</div>

						{* SPIDERKILL - disable wiki Import
							{if $gBitUser->hasPermission( 'p_wiki_admin' )}
								<tr><td>
									{tr}Import file{/tr}:</td><td>
									<input name="userfile1" type="file" />
									{ * <a href="{$smarty.const.WIKI_PKG_URL}export_wiki_pages.php?page_id={$pageInfo.page_id}&amp;all=1">{tr}export all versions{/tr}</a> * }
								</td></tr>
							{/if} { * end upload file row * }
						*}

							<div class="form-group submit">
								<input type="submit" class="btn btn-default" name="do_suck" value="{tr}Import{/tr}" />
							</div>
						{/legend}
					{/jstab}
				{/if}
			{/jstabs}
			<div class="form-group submit">
				{forminput}
					<input type="submit" class="btn btn-primary" name="fSavePage" value="{tr}Save{/tr}" /> <input type="submit" class="btn pull-right" name="fCancel" value="{tr}Cancel{/tr}" /> <input type="submit" class="btn btn-default" name="preview" value="{tr}Preview{/tr}" />
				{/forminput}
			</div>

		{/form}
	</div><!-- end .body -->
</div><!-- end .admin -->
{/strip}
