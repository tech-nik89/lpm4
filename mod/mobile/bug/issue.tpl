<form action="" method="get">
	<input type="hidden" name="mod" value="bug" />
	<p align="right">
		{$lang.goto} #
		<input type="text" name="issueid" value="" style="width:70px;" />
		<input type="submit" name="sbtGotoIssue" value="{$lang.go}" />
	</p>
</form>

<div class="headline">{$lang.issue} #{$issue.issueid}</div>

<table width="100%" border="0" cellpadding="7">
	<tr>
		<td><strong>{$lang.project}:</strong></td>
		<td>{$project.name}</td>
		<td><strong>{$lang.category}:</strong></td>
		<td>{$issue.category}</td>
	</tr>
	<tr>
		<td><strong>{$lang.reproducible}:</strong></td>
		<td>{$lang.reproducibles[$issue.reproducible]}</td>
		<td><strong>{$lang.effect}:</strong></td>
		<td>{$lang.effects[$issue.effect]}</td>
	</tr>
	<tr>
		<td><strong>{$lang.priority}:</strong></td>
		<td>{$lang.priorities[$issue.priority]}</td>
		<td><strong>{$lang.reporter}:</strong></td>
		<td>{$issue.reporter.nickname}</td>
	</tr>
	<tr>
		<td><strong>{$lang.date}:</strong></td>
		<td>{$issue.timestamp|date_format}</td>
		<td><strong>{$lang.state}:</strong></td>
		<td style="background-color:{$colors[$issue.state]}">{$lang.states[$issue.state]}</td>
	</tr>
	<tr>
		<td><strong>{$lang.assignedTo}:</strong></td>
		<td>
			{foreach from=$assignToList item=user}
				{if $user.userid == $issue.assignedto}
					{$user.nickname}
				{/if}
			{/foreach}
		</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td><strong>{$lang.summary}:</strong></td>
		<td colspan="3">{$issue.summary}</td>
	</tr>
</table>

<div class="headline">{$lang.description}</div>
<p>{$issue.description}</p>

{if $issue.additional != ""}
	<div class="headline">{$lang.additional}</div>
	<p>{$issue.additional}</p>
{/if}
	
{if $isallowed}
	<div class="headline">{$lang.options}</div>
	<form action="" method="post">
		<table width="100%" border="0">
			<tr>
				<td>
					<input type="submit" name="sbtAssignTo" value="{$lang.assignTo}:" />
					<select name="txtAssignTo">
						{foreach from=$assignToList item=user}
							<option value="{$user.userid}"{if $user.userid == $issue.assignedto} selected="selected"{/if}>
							{$user.nickname}</option>
						{/foreach}
					</select>
				</td>
				<td>
					<input type="submit" name="sbtSetState" value="{$lang.setState}:" />
					<select name="txtState">
						{for $i=0; $i < sizeof($lang.states); $i++ }
							<option value="{$i}"{if $issue.state == $i} selected="selected"{/if}>{$lang.states[$i]}</option>
						{/for}
					</select>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<input type="button" name="btnRemove" value="{$lang.remove}" 
						onclick="$('#divRemoveConfirm').show();" />
					<span id="divRemoveConfirm" style="display:none;">
						{$lang.remove_confirm}
						<input type="submit" name="sbtRemove" value="{$lang.yes}" />	
					</span>
				</td>
			</tr>
		</table>
	</form>
{/if}

<div class="headline">{$lang.notes}</div>
{foreach from=$notes item=note}
	
	<p>
		<strong>{$note.nickname}</strong><br />
		{$note.text}
	</p>
	
{/foreach}

{if $isallowed || $iReporter}
	<form action="" method="post">
		<textarea name="txtNewComment" rows="12" cols="30" style="width:100%;"></textarea>
		<p>
			<input type="submit" name="sbtAddComment" value="{$lang.addNote}" />
		</p>
	</form>
{/if}