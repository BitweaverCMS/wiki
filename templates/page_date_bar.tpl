{strip}
{if !$gBitSystem->isFeatureActive( 'wiki_hide_date' )}
	<div class="date">
		Created by: {displayname user=$gContent->mInfo.creator_user user_id=$gContent->mInfo.user_id real_name=$gContent->mInfo.creator_real_name},&nbsp;
		Last modification: {$gContent->mInfo.last_modified|reltime}
		{if $gContent->mInfo.modifier_user_id!=$gContent->mInfo.user_id}
			&nbsp;
			by {displayname user=$gContent->mInfo.modifier_user user_id=$gContent->mInfo.modifier_user_id real_name=$gContent->mInfo.modifier_real_name}
		{/if}
	</div>
{/if}
{/strip}
