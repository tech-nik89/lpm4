sep=,
{$stat.month},{$lang.visitors},{$lang.weekday}
{foreach from=$stat.days item=day}
{$day.day}.,{$day.value},{$day.weekday}
{/foreach}