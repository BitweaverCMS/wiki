{* $Header: /cvsroot/bitweaver/_bit_wiki/modules/mod_comm_received_objects.tpl,v 1.2 2005/07/17 17:36:46 squareing Exp $ *}
{if $gBitSystem->isFeatureActive( 'feature_comm' )}
{bitmodule title="$moduleTitle" name="comm_received_objects"}
	<table class="module box"><tr>
		<td valign="top">{tr}Pages:{/tr}</td>
		<td>&nbsp;{$modReceivedPages}</td>
	</tr></table>
{/bitmodule}
{/if}