{* $Header: /cvsroot/bitweaver/_bit_wiki/modules/mod_comm_received_objects.tpl,v 1.3 2006/02/09 14:52:47 squareing Exp $ *}
{if $gBitSystem->isFeatureActive( 'feature_comm' )}
{bitmodule title="$moduleTitle" name="comm_received_objects"}
	<table class="module box"><tr>
		<td valign="top">{tr}Pages:{/tr}</td>
		<td>&nbsp;{$modReceivedPages}</td>
	</tr></table>
{/bitmodule}
{/if}
