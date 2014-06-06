{strip}
{if count($showstructs) ne 0}
	<div class="form-group">
		{formlabel label="Structures"}
		{forminput}
			<a id="show_strct" style="display:block;" href="javascript:show('showstructs');show('hide_strct');hide('show_strct')">{tr}show books{/tr}</a>
			<a id="hide_strct" style="display:none;" href="javascript:hide('showstructs');show('show_strct');hide('hide_strct')">{tr}hide books{/tr}</a>
			<div id="showstructs" style="display:none;">
				<div style="float:left;padding:5px 5px 5px 0;">
					{foreach from=$showstructs item=struct }
						{$struct.title|escape}<br />
					{/foreach}
				</div>
			</div>
			{formhelp note="If you would like your page to be part of a particular wikibook, please select one here."}
		{/forminput}
		<div class="clear"></div>
	</div>
{/if}
{/strip}
