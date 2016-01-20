<form action="" method="get">
	<input type="hidden" name="mod" value="bug" />
	<p align="right">
		{$lang.goto} #
		<input type="text" name="issueid" value="" style="width:70px;" />
		<input type="submit" name="sbtGotoIssue" value="{$lang.go}" />
	</p>
</form>

<div class="headline">{$project.name}</div>

<p>
<form action="" method="post">
	<table width="100%" border="0">
		<tr>
			<td>{$lang.state}:</td>
			<td>
				<select name="txtState">
					<option value="-1">-</option>
					{for $i=0; $i < sizeof($lang.states); $i++ }
						<option value="{$i}"{if $filter.state == $i} selected="selected"{/if}>{$lang.states[$i]}</option>
					{/for}
				</select>
			</td>
			<td>{$lang.assignedTo}:</td>
			<td>
				<select name="txtAssignedTo">
					<option value="-1">-</option>
					{foreach from=$assignToList item=user}
						<option value="{$user.userid}"{if $user.userid == $filter.assignedto} selected="selected"{/if}>
						{$user.nickname}</option>
					{/foreach}
				</select>
			</td>
		</tr>
		<tr>
			<td>{$lang.priority}:</td>
			<td>
				<select name="txtPriority">
					<option value="-1">-</option>
					{for $i=0; $i < sizeof($lang.priorities); $i++ }
						<option value="{$i}"{if $filter.priority == $i} selected="selected"{/if}>{$lang.priorities[$i]}</option>
					{/for}
				</select>
			</td>
			<td>{$lang.effect}:</td>
			<td>
				<select name="txtEffect">
					<option value="-1">-</option>
					{for $i=0; $i < sizeof($lang.effects); $i++ }
						<option value="{$i}"{if $filter.effect == $i} selected="selected"{/if}>{$lang.effects[$i]}</option>
					{/for}
				</select>
			</td>
		</tr>
		<tr>
			<td>{$lang.category}:</td>
			<td>
				<select name="txtCategory">
					<option value="-1">-</option>
					{foreach from=$categories item=category}
						<option value="{$category.categoryid}"{if $filter.categoryid == $category.categoryid} selected="selected"{/if}>{$category.name}</option>
					{/foreach}
				</select>
			</td>
			<td>{$lang.orderby}:</td>
			<td>
				<select name="txtOrder">
					<option value=""{if $filter.order == ""} selected="selected"{/if}>-</option>
					<option value="state"{if $filter.order == "state"} selected="selected"{/if}>{$lang.state}</option>
					<option value="assignedto"{if $filter.order == "assignedto"} selected="selected"{/if}>{$lang.assignedTo}</option>
					<option value="priority"{if $filter.order == "priority"} selected="selected"{/if}>{$lang.priority}</option>
					<option value="categoryid"{if $filter.order == "categoryid"} selected="selected"{/if}>{$lang.category}</option>
					<option value="effect"{if $filter.order == "effect"} selected="selected"{/if}>{$lang.effect}</option>
				</select>
				<select name="txtDirection">
					<option value=""{if $filter.direction == ""} selected="selected"{/if}>-</option>
					<option value="ASC"{if $filter.direction == "ASC"} selected="selected"{/if}>{$lang.ascending}</option>
					<option value="DESC"{if $filter.direction == "DESC"} selected="selected"{/if}>{$lang.descending}</option>
				</select>
			</td>
		</tr>
	</table>
	<p align="left">
		<input type="submit" name="sbtFilter" value="{$lang.filter}" />
		<input type="submit" name="sbtClear" value="{$lang.clear}" />
	</p>
</form>
</p>
<p>{$pages}</p>
<p>
<table width="100%" border="0" cellspacing="1" cellpadding="4">
	<tr>
		<th>ID</th>
		<th>#</th>
		<th>P</th>
		<th>{$lang.category}</th>
		<th>{$lang.effect}</th>
		<th>{$lang.state}</th>
		<th>{$lang.summary}</th>
	</tr>
	
	{foreach from=$issues item=issue}
		<tr>
			<td><a href="{$issue.url}">{$issue.issueid_str}</a></td>
			<td>{$issue.comments}</td>
			<td>{$issue.priority}</td>
			<td>{$issue.category_str}</td>
			<td>{$lang.effects[$issue.effect]}</td>
			<td style="background-color:{$colors[$issue.state]};">{$lang.states[$issue.state]}</td>
			<td>{$issue.summary}</td>
		</tr>
	{/foreach}
</table>
</p>
<p>{$pages}</p>