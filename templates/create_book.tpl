{* $Header: /cvsroot/bitweaver/_bit_wiki/templates/create_book.tpl,v 1.1 2005/06/19 06:12:45 bitweaver Exp $ *}
{strip}
<div class="floaticon">{bithelp}</div>

<div class="admin structure">
	<div class="header">
		<h1>{tr}Wiki Books{/tr}</h1>
	</div>

	<div class="body">
		{form legend="Create new Wiki Book"}
			<div class="row">
				{formfeedback error=`$errors.title`}
				{formlabel label="Book Title" for="name"}
				{forminput}
					<input type="text" name="name" id="name" size="50" maxlength="240"/>
					{formhelp note="Enter the name of your WikiBook."}
				{/forminput}
			</div>

			<div class="row">
				{formlabel label="Table of Contents<br />(optional)" for="chapters"}
				{forminput}
					<textarea rows="10" cols="60" name="chapters" id="chapters"></textarea>
					{formhelp note="To enter the table of contents manually, you can add WikiPage names on separate lines. Pages that don't exist will be added automagically and you can edit them later."}
				{/forminput}
			</div>

			<div class="row submit">
				<input type="submit" value="{tr}create new book{/tr}" name="createstructure" />
			</div>
		{/form}
	</div><!-- end .body -->
</div><!-- end .structure -->
{/strip}
