{* $Header: /cvsroot/bitweaver/_bit_wiki/modules/mod_comm_received_objects.tpl,v 1.1.1.1.2.1 2005/07/15 12:01:29 squareing Exp $ *}
{if $gBitSystem->isFeatureActive( 'feature_comm' )}
{bitmodule title="$moduleTitle" name="comm_received_objects"}
	<table class="module box"><tr>
		<td valign="top">{tr}Pages:{/tr}</td>
		<td>&nbsp;{$modReceivedPages}</td>
	</tr></table>
{/bitmodule}
{/if}