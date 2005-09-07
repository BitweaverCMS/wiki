{if $gBitSystemPrefs.hide_wiki_date ne 'y'}
	<div class="date">
		{tr}Created by {displayname user=$pageInfo.creator_user user_id=$pageInfo.creator_user_id real_name=$pageInfo.creator_real_name}, Last modification by {displayname user=$pageInfo.modifier_user user_id=$pageInfo.modifier_user_id real_name=$pageInfo.modifier_real_name} on {$pageInfo.last_modified|bit_short_datetime}{/tr}
	</div>
{/if}
