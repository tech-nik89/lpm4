sep=,
{$stat.month},{$lang.downloads}
{foreach from=$stat.days item=day}
{$day.day}.,{$day.counter}
{/foreach}