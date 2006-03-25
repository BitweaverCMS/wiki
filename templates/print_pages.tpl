{strip}
<div class="display wiki">
	<div class="header">
		<h1>{tr}Print multiple pages{/tr}</h1>
	</div>

	<div class="body">
			{form legend="Print Wiki Pages"}
				<input type="hidden" name="sendarticles" value="{$form_sendarticles|escape}" />
				<input type="hidden" name="printpages" value="{$form_printpages|escape}" />

				<div class="row">
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

				<div class="row">
					{formlabel label="Filter" for="find"}
					{forminput}
						<input type="text" name="find" id="find" value="{$find|escape}" />
						&nbsp;<input type="submit" name="filter" value="{tr}filter{/tr}" />
						{formhelp note="To find specific pages more easily, you can apply a filter here."}
					{/forminput}
				</div>

				<div class="row submit">
					<input type="submit" name="addpage" value="{tr}add page{/tr}" />
					&nbsp;<input type="submit" name="clearpages" value="{tr}clear pages{/tr}" />
				</div>
			{/form}

			{form legend="Selected Wiki Pages" ipackage="wiki" ifile="print_multi_pages.php"}
				<input type="hidden" name="printpages" value="{$form_printpages|escape}" />
				
				<div class="row">
					<ul>
						{section name=ix loop=$printpages}
							<li>{$printpages[ix]}</li>
						{/section}
					</ul>
				</div>
				
				<div class="row submit">
					<input type="submit" name="print" value="{tr}print{/tr}" />
				</div>
			{/form}
	</div><!-- end .body -->
</div><!-- end .wiki -->
{/strip}

{*
		<div class="other box">
		<div class="boxtitle">{tr}Filter{/tr}</div>
		<div class="boxcontent">
		<form action="{$smarty.const.WIKI_PKG_URL}print_pages.php" method="post">
		<input type="hidden" name="sendarticles" value="{$form_sendarticles|escape}" />
		<input type="hidden" name="printpages" value="{$form_printpages|escape}" />
		{tr}Filter{/tr}:&nbsp;<input type="text" name="find" value="{$find|escape}" />&nbsp;<input type="submit" name="filter" value="{tr}filter{/tr}" /><br />
		</form>
		</div>
		</div>

		<div class="other box">
		<div class="boxtitle">{tr}Print Wiki Pages{/tr}</div>
		<div class="boxcontent">
		<form action="{$smarty.const.WIKI_PKG_URL}print_pages.php" method="post">
		<input type="hidden" name="printpages" value="{$form_printpages|escape}" />
		<input type="hidden" name="find" value="{$find|escape}" />
		<select name="title">
		{section name=ix loop=$pages}
		  <option value="{$pages[ix].content_id}">{$pages[ix].title|escape}</option>
		{/section}
		</select>
		<input type="submit" name="addpage" value="{tr}add page{/tr}" />
		<input type="submit" name="clearpages" value="{tr}clear{/tr}" />
		</form>
		</div>

		<div class="boxcontent">
		<b>{tr}Print following pages{/tr}</b>
		<ul>
		{section name=ix loop=$printpages}
		  <li>{$printpages[ix]}</li>
		{/section}
		</ul>
		</div>

		<div class="boxcontent">
		<form method="post" action="{$smarty.const.WIKI_PKG_URL}print_multi_pages.php">
		<input type="hidden" name="printpages" value="{$form_printpages|escape}" />
		<input type="submit" name="print" value="{tr}print{/tr}" />
		</form>
		</div>

	</div>
</div>
*}
