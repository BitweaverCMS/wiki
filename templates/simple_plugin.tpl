{* $Header: /cvsroot/bitweaver/_bit_wiki/templates/simple_plugin.tpl,v 1.1 2005/06/19 06:12:45 bitweaver Exp $ *}

{box title=$title}

    {foreach key=t item=i from=$listcat}
        <b>{$t}:</b>
        {section name=o loop=$i}
            <a href="{$i[o].href}" title="{tr}Created{/tr} {$i[o].created|bit_long_date}">
                {$i[o].name}
            </a>
            {if $smarty.section.o.index ne $smarty.section.o.total - 1} &middot; {/if}
        {/section}<br />
    {/foreach}

{/box}
