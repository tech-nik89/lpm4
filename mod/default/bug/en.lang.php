<?php
	
	$lang['bugtracker'] = 'Bugtracker';
	$lang['effect'] = 'Impact';
	$lang['effects'] = array('Minor errors', 'fatal error', 'feature-request', 'trivial', 'error in the text', 'crash', 'Blocker');
	
	$lang['summary'] = 'Summary';
	$lang['category'] = 'Category';
	
	$lang['reproducible'] = 'Reproducible';
	$lang['reproducibles'] = array('Not tested ',' Always ',' Sometimes ',' Random ',' Can not Reproduce', 'N/A');
	
	$lang['priority'] = 'Priority';
	$lang['priorities'] = array('No ',' low ','medium ',' high ',' Urgent ',' now ');
	
	$lang['states'] = array('New ',' assigned ',' feedback ',' Approved ',' confirmed ',' Done ',' Closed ');
	
	$lang['additional'] = 'Additional information';
	
	$lang['issue'] = 'Problem';
	$lang['issues'] = 'Problems';
	$lang['addIssue'] = 'Problem type';
	$lang['submitIssue'] = 'Submit Report';
	$lang['addIssueDone'] = 'The report has been submitted successfully.';
	
	$lang['project'] = 'Project';
	$lang['projects'] = 'Projects';
	$lang['addProject'] = 'New Project';
	$lang['addProjectDone'] = 'The project was successfully created.';
	
	$lang['editProject'] = 'Edit project';
	$lang['editProjectDone'] = 'Project has been successfully';
	
	$lang['deleteProject'] = 'Delete Project';
	$lang['deleteProjectAsk'] = 'The project will be really deleted?';
	$lang['deleteProjectDone'] = 'Project was successfully deleted';
	
	$lang['categories'] = 'Categories';
	
	$lang['reporter'] = 'Reporter';
	$lang['options'] = 'Options';
	
	$lang['assignTo'] = 'Assign to';
	$lang['assignedTo'] = 'Assigned to';
	$lang['setState'] = 'Set Status';
	
	$lang['filter'] = 'Filters';
	$lang['clear'] = 'Reset';
	
	$lang['notes'] = 'Notes';
	$lang['addNote'] = 'Add New Note';
	
	$lang['orderby'] = 'Sort by';
	$lang['ascending'] = 'Ascending';
	$lang['descending'] = 'Descending';
	
	$lang['goto'] = 'Go to';
	$lang['search_for'] = 'Keyword';
	
	$lang['mail_notify_assignment_subject'] = 'Bugtracker: Allocation';
	$lang['mail_notify_assignment_text'] = '<p>You have been assigned a task in the bugtracker.</p>
		<p>ID: {$issueid}<br />Project: {$project}</br />Summary: {$summary}</p>
		<p><a href="{$url}">To the case</p>';
	
	$lang['remove_confirm'] = 'Do you want to remove this entry?';
	$lang['cleanup'] = 'Clean up';
	$lang['remove_closed'] = 'Closed remove entries';
	$lang['remove_closed_done'] = 'Entries closed successfully removed.';
?>