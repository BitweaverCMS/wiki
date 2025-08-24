{strip}
<div class="display wiki">
	<div class="header">
		<h1>Print multiple pages</h1>
	</div>

	<div class="body">
			{form legend="Print Wiki Pages"}
				<input type="hidden" name="sendarticles" value="{$form_sendarticles|escape}" />
				<input type="hidden" name="printpages" value="{$form_printpages|escape}" />

				<div class="form-group">
					{formlabel label="Wiki Pages" for="pages"}
					{forminput}
						<select name="title" id="pages">
							{section name=ix loop=$pages}
								<option value="{$pages[ix].content_id}">{$pages[ix].title|escape}</option>
							{/section}
						</select>
						{formhelp note="Add the pages you want to print as one."}
					{/forminput}
				</div>

				<div class="form-group">
					{formlabel label="Filter" for="find"}
					{forminput}
						<input type="text" name="find" id="find" value="{$find|escape}" />
						&nbsp;<input type="submit" class="btn btn-default" name="filter" value="filter" />
						{formhelp note="To find specific pages more easily, you can apply a filter here."}
					{/forminput}
				</div>

				<div class="form-group submit">
					<input type="submit" class="btn btn-default" name="addpage" value="add page" />
					&nbsp;<input type="submit" class="btn btn-default" name="clearpages" value="clear pages" />
				</div>
			{/form}

			{form legend="Selected Wiki Pages" ipackage="wiki" ifile="print_multi_pages.php"}
				<input type="hidden" name="printpages" value="{$form_printpages|escape}" />
				
				<div class="form-group">
					<ul>
						{section name=ix loop=$printpages}
							<li>{$printpages[ix]}</li>
						{/section}
					</ul>
				</div>
				
				<div class="form-group submit">
					<input type="submit" class="btn btn-default" name="print" value="print" />
				</div>
			{/form}
	</div><!-- end .body -->
</div><!-- end .wiki -->
{/strip}
