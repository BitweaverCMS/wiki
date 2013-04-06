{strip}
<div class="floaticon">{bithelp}</div>

<div class="admin structure">
	<div class="header">
		<h1>{tr}Wiki Books{/tr}</h1>
	</div>

	<div class="body">
		{form legend="Create new Wiki Book"}
			<div class="control-group">
				{formfeedback error=$errors.title}
				{formlabel label="Book Title" for="name"}
				{forminput}
					<input type="text" name="name" id="name" size="50" maxlength="240" value="{$name}"/>
					{formhelp note="Enter the name of your WikiBook."}
				{/forminput}
			</div>

			<div class="control-group">
				{formlabel label="Table of Contents<br />(optional)" for="chapters"}
				{forminput}
					<textarea rows="10" cols="50" name="chapters" id="chapters">{$chapters}</textarea>
					{formhelp note="To enter the table of contents manually, you can add WikiPage names on separate lines. Pages that don't exist will be added automagically and you can edit them later."}
				{/forminput}
			</div>

			<div class="control-group submit">
				<input type="submit" class="btn" value="{tr}Create new book{/tr}" name="createstructure" />
			</div>
		{/form}
	</div><!-- end .body -->
</div><!-- end .structure -->
{/strip}
