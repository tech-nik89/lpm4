sep=,
{$stat.month} {$stat.year},{$lang.downloads}
{foreach from=$stat.days item=day}
{$day.day}.,{$day.counter}
{/foreach}