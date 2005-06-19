<h2>{tr}Preview{/tr}: {$title}</h2>
{if $gBitSystemPrefs.feature_wiki_description eq 'y'}
<small>{$description}</small>
{/if}
<div  class="wikibody">{$parsed}</div>
{if $has_footnote}
<div  class="wikibody">{$parsed_footnote}</div>
{/if}
