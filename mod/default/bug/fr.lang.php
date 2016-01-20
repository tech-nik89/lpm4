<?php
	
	$lang['bugtracker'] = 'Gestionnaire de bugs';
	$lang['effect'] = 'Impact';
	$lang['effects'] = array('Les erreurs mineures','Erreur fatale fonction-demande','trivial','erreur dans le texte','accident','Blocker');
	
	$lang['summary'] = 'Sommaire';
	$lang['category'] = 'Categorie';
	
	$lang['reproducible'] = 'Reproductibles';
	$lang['reproducibles'] = array('Non test&eacute;','Toujours','Parfois','au hasard','Impossible &agrave; reproduire', 'N/A');
	
	$lang['priority'] = 'Priorit&eacute;';
	$lang['priorities'] = array('Non','faible','moyen','&eacute;lev&eacute;','urgent','maintenant ');
	
	$lang['states'] = array('Nouveau','attribu&eacute;','feedback','approuv&eacute;','confirm&eacute;','Termin&eacute;','ferm&eacute; ');
	
	$lang['additional'] = 'Additional information';
	
	$lang['issue'] = 'Probleme';
	$lang['issues'] = 'Problemes';
	$lang['addIssue'] = 'Probleme type';
	$lang['submitIssue'] = 'Soumettre le rapport';
	$lang['addIssueDone'] = 'Le rapport a &eacute;t&eacute; soumis avec succ&egrave;s.';
	
	$lang['project'] = 'Projet';
	$lang['projects'] = 'Projets';
	$lang['addProject'] = 'Nouveau projet';
	$lang['addProjectDone'] = 'Le projet a &eacute;t&eacute; cr&eacute;&eacute; avec succ&egrave;s.';
	
	$lang['editProject'] = '&eacute;dition projet';
	$lang['editProjectDone'] = 'Le projet a &eacute;t&eacute; publi&eacute; avec succ&egrave;s';
	
	$lang['deleteProject'] = 'Supprimer un projet';
	$lang['deleteProjectAsk'] = 'Voulez-vous supprimer le projet?';
	$lang['deleteProjectDone'] = 'Projet est supprim&eacute; avec succ&egrave;s';
	
	$lang['categories'] = 'Categories';
	
	$lang['reporter'] = 'Reporter';
	$lang['options'] = 'Options';
	
	$lang['assignTo'] = 'Assigner &agrave;';
	$lang['assignedTo'] = 'Assign&eacute; &agrave;';
	$lang['setState'] = 'D&eacute;finir le statut';
	
	$lang['filter'] = 'Filtre';
	$lang['clear'] = 'R&eacute;initialiser';
	
	$lang['notes'] = 'Notes';
	$lang['addNote'] = 'Ajouter une nouvelle note';
	
	$lang['orderby'] = 'Trier par';
	$lang['ascending'] = 'Croissant';
	$lang['descending'] = 'Descroissant';
	
	$lang['goto'] = 'Allez &agrave;';
	$lang['search_for'] = 'Mots-cl&eacute;s';
	
	$lang['mail_notify_assignment_subject'] = 'Gestionnaire de bugs: Attribution';
	$lang['mail_notify_assignment_text'] = '<p>Vous avez &eacute;t&eacute; charg&eacute; d une tâche dans le gestionnaire de bugs.</p>
		<p>ID: {$issueid}<br />Projet: {$project}</br />Sommaire: {$summary}</p>
		<p><a href="{$url}">Pour le cas</p>';
	
	$lang['remove_confirm'] = 'Voulez-vous supprimer cette entr&eacute;e?';
	$lang['cleanup'] = 'Nettoyer';
	$lang['remove_closed'] = 'Fermer les entr&eacute;e supprim&eacute;';
	$lang['remove_closed_done'] = 'Entr&eacute;es ferm&eacute;e supprim&eacute; avec succ&egrave;s.';
?>