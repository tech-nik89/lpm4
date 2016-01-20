<form action="" method="get">
	<input type="hidden" name="mod" value="bug" />
	<p align="right">
		{$lang.goto} #
		<input type="text" name="issueid" value="" style="width:70px;" />
		<input type="submit" name="sbtGotoIssue" value="{$lang.go}" />
	</p>
</form>

<div class="headline">{$lang.addIssue}</div>

<form action="" method="post">

	<table border="0" width="100%">
		
		<tr>
			<td width="170">*{$lang.category}</td>
			<td>
				<select name="category">
					{foreach from=$categories item=category}
						<option value="{$category.categoryid}"{if $issue.categoryid == $category.categoryid} selected="selected"{/if}>{$category.name}</option>
					{/foreach}
				</select>
			</td>
		</tr>
		
		<tr>
			<td>{$lang.reproducible}</td>
			<td>
				<select name="reproducible">
					{for $i=0; $i < sizeof($lang.reproducibles); $i++ }
						<option value="{$i}"{if $issue.reproducible == $i} selected="selected"{/if}>{$lang.reproducibles[$i]}</option>
					{/for}
				</select>
			</td>
		</tr>
		
		<tr>
			<td>{$lang.effect}</td>
			<td>
				<select name="effect">
					{for $i=0; $i < sizeof($lang.effects); $i++ }
						<option value="{$i}"{if $issue.effect == $i} selected="selected"{/if}>{$lang.effects[$i]}</option>
					{/for}
				</select>
			</td>
		</tr>
		
		<tr>
			<td>{$lang.priority}</td>
			<td>
				<select name="priority">
					{for $i=0; $i < sizeof($lang.priorities); $i++ }
						<option value="{$i}"{if $issue.priority == $i} selected="selected"{/if}>{$lang.priorities[$i]}</option>
					{/for}
				</select>
			</td>
		</tr>
		
		<tr>
			<td>*{$lang.summary}</td>
			<td><input type="text" name="summary" value="{$issue.summary}" style="width:100%;" /></td>
		</tr>
		
		<tr>
			<td>*{$lang.description}</td>
			<td>
				<textarea name="description" rows="15" cols="100" style="width:100%;">{$issue.description}</textarea>
			</td>
		</tr>
		
		<tr>
			<td>{$lang.additional}</td>
			<td>
				<textarea name="additional" value="" rows="15" cols="100" style="width:100%;">{$issue.additional}</textarea>
			</td>
		</tr>
		
	</table>
		
	<p><input type="submit" name="submit" value="{$lang.submitIssue}" /></p>
</form>