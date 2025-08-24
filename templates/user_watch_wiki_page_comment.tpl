The page {$mail_page} was commented by {$mail_user} at {$mail_date|bit_short_datetime}

You can view the page following this link:
{$mail_machine}?page={$mail_page|escape:"url"}
Title: {$mail_title}
Comment: {$mail_comment}

If you don't want to receive these notifications follow this link:
{$mail_machine_raw}/{$smarty.const.USERS_PKG_URL}watches.php?hash={$mail_hash}
