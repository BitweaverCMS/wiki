The page {$mail_page} was changed at
{$mail_date|bit_short_datetime}


You can view the page by following this link:
    {$mail_machine}{$smarty.const.WIKI_PKG_URL}index.php?page={$mail_page|escape:"url"}


You can edit the page by following this link:
    {$mail_machine}{$smarty.const.WIKI_PKG_URL}edit.php?page={$mail_page|escape:"url"}


You can view a diff back to the previous version by following
this link:
    {$mail_machine}{$smarty.const.WIKI_PKG_URL}page_history.php?page={$mail_page|escape:"url"}&diff2={$mail_last_version}


Comment: {$mail_comment}



The new page content follows below.

***********************************************************

{$mail_pagedata}
