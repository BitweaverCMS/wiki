{* $Header: /cvsroot/bitweaver/_bit_wiki/modules/mod_comm_received_objects.tpl,v 1.1 2005/06/19 06:12:45 bitweaver Exp $ *}
{if $gBitSystemPrefs.feature_comm eq 'y'}
{bitmodule title="$moduleTitle" name="comm_received_objects"}
	<table class="module box"><tr>
		<td valign="top">{tr}Pages:{/tr}</td>
		<td>&nbsp;{$modReceivedPages}</td>
	</tr></table>
{/bitmodule}
{/if}