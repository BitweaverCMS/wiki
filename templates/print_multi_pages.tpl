{* $Header: /cvsroot/bitweaver/_bit_wiki/templates/print_multi_pages.tpl,v 1.1 2005/06/19 06:12:45 bitweaver Exp $ *}

{include file="bitpackage:kernel/header.tpl"}
<div id="printpage">
  <h1>Wiki Pages</h1>
  {section name=ix loop=$pages}
    <h2>{$pages[ix].title}</h2>
    <div class="content">{$pages[ix].parsed}</div>
  <hr />
  {/section}
</div>
{include file="bitpackage:kernel/footer.tpl"}
