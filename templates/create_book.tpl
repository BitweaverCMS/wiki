{strip}
<div class="floaticon">{bithelp}</div>

<div class="admin structure">
	<div class="header">
		<h1>{tr}Wiki Books{/tr}</h1>
	</div>

	<div class="body">
		{form legend="Create new Wiki Book"}
			<div class="form-group">
				{formfeedback error=$errors.title}
				{formlabel label="Book Title" for="name"}
				{forminput}
					<input class="form-control" type="text" name="name" id="name" size="50" maxlength="240" value="{$name}"/>
					{formhelp note="Enter the name of your WikiBook."}
				{/forminput}
			</div>

			<div class="form-group">
				{formlabel label="Table of Contents (optional)" for="chapters"}
				{forminput}
					<textarea class="form-control" rows="10" name="chapters" id="chapters">{$chapters}</textarea>
					{formhelp note="To enter the table of contents manually, you can add WikiPage names on separate lines. Pages that don't exist will be created automatically and you can edit them later."}
				{/forminput}
			</div>

			<div class="form-group submit">
				<input type="submit" class="btn btn-default" value="{tr}Create new book{/tr}" name="createstructure" />
			</div>
		{/form}
	</div><!-- end .body -->
</div><!-- end .structure -->
{/strip}
