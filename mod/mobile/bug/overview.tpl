<form action="" method="get">
	<input type="hidden" name="mod" value="bug" />
	<p align="right">
		{$lang.goto} #
		<input type="text" name="issueid" value="" style="width:70px;" />
		<input type="submit" name="sbtGotoIssue" value="{$lang.go}" />
	</p>
</form>

<div class="headline">{$lang.bugtracker}</div>

<table width="100%" border="0" cellpadding="5" cellspacing="1">
	
	<tr>
		<th>{$lang.project}</th>
		<th width="15%">{$lang.issues}</th>
		<th width="15%">{$lang.states[0]}</th>
		<th width="15%">{$lang.states[1]}</th>
		<th width="15%">{$lang.states[5]}</th>
	</tr>
	
	{foreach from=$projects item=project}
		<tr>
			<td><a href="{$project.url}">{$project.name}</a></td>
			<td>{$project.issues}</td>
			<td>{$project.issues_new}</td>
			<td>{$project.issues_assigned}</td>
			<td>{$project.issues_solved} ({$project.issues_solved_relative}%)</td>
		</tr>
	{/foreach}
	
</table>