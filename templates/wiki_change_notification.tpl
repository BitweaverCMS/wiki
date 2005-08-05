{tr}The page {$mail_page} was changed at
{$mail_date|bit_short_datetime}{/tr}


{tr}You can view the page by following this link:
    {$mail_machine}/{$smarty.const.WIKI_PKG_URL}index.php?page={$mail_page|escape:"url"}{/tr}


{tr}You can edit the page by following this link:
    {$mail_machine}/{$smarty.const.WIKI_PKG_URL}edit.php?page={$mail_page|escape:"url"}{/tr}


{tr}You can view a diff back to the previous version by following
this link:
    {$mail_machine}/{$smarty.const.WIKI_PKG_URL}page_history.php?page={$mail_page|escape:"url"}&amp;diff2={$mail_last_version}{/tr}


{tr}Comment:{/tr} {$mail_comment}



{tr}The new page content follows below.{/tr}

***********************************************************

{$mail_pagedata}
