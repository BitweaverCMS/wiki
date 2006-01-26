{if !$gBitSystem->isFeatureActive( 'hide_wiki_date' )}
	<div class="date">
		{tr}Created by{/tr}: {displayname user=$pageInfo.creator_user user_id=$pageInfo.user_id real_name=$pageInfo.creator_real_name},
		{tr}Last modification on{/tr} {$pageInfo.last_modified|bit_short_datetime}
		{if $pageInfo.modifier_user_id!=$pageInfo.user_id}
			{tr}by{/tr} {displayname user=$pageInfo.modifier_user user_id=$pageInfo.modifier_user_id real_name=$pageInfo.modifier_real_name}
		{/if}
	</div>
{/if}
