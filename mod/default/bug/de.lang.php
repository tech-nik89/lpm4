<?php
	
	$lang['bugtracker'] = 'Bugtracker';
	$lang['effect'] = 'Auswirkung';
	$lang['effects'] = array('Kleinerer Fehler', 'Schwerer Fehler', 'Feature-Wunsch', 'Trivial', 'Fehler im Text', 'Absturz', 'Blocker');
	
	$lang['summary'] = 'Zusammenfassung';
	$lang['category'] = 'Kategorie';
	
	$lang['reproducible'] = 'Reproduzierbar';
	$lang['reproducibles'] = array('Nicht getestet', 'Immer', 'Manchmal', 'Zuf&auml;llig', 'Nicht reproduzierbar', 'N/A');
	
	$lang['priority'] = 'Priorit&auml;t';
	$lang['priorities'] = array('Keine', 'Niedrig', 'Mittel', 'Hoch', 'Dringend', 'Sofort');
	
	$lang['states'] = array('Neu', 'Zugewiesen', 'R&uuml;ckmeldung', 'Anerkannt', 'Best&auml;tigt', 'Erledigt', 'Geschlossen');
	
	$lang['additional'] = 'Zus&auml;tzliche Informationen';
	
	$lang['issue'] = 'Problem';
	$lang['issues'] = 'Probleme';
	$lang['addIssue'] = 'Problem eingeben';
	$lang['submitIssue'] = 'Bericht absenden';
	$lang['addIssueDone'] = 'Der Bericht wurde erfolgreich abgesendet.';
	
	$lang['project'] = 'Projekt';
	$lang['projects'] = 'Projekte';
	$lang['addProject'] = 'Neues Projekt';
	$lang['addProjectDone'] = 'Das Projekt wurde erfolgreich angelegt.';
	
	$lang['editProject'] = 'Projekt bearbeiten';
	$lang['editProjectDone'] = 'Projekt wurde erfolgreich ge&auml;ndert';
	
	$lang['deleteProject'] = 'Projekt l&ouml;schen';
	$lang['deleteProjectAsk'] = 'Soll das Projekt wirklich gel&ouml;scht werden?';
	$lang['deleteProjectDone'] = 'Projekt wurde erfolgreich gel&ouml;scht';
	
	$lang['categories'] = 'Kategorien';
	
	$lang['reporter'] = 'Reporter';
	$lang['options'] = 'Optionen';
	
	$lang['assignTo'] = 'Zuweisen zu';
	$lang['assignedTo'] = 'Zugewiesen zu';
	$lang['setState'] = 'Status setzen';
	
	$lang['filter'] = 'Filtern';
	$lang['clear'] = 'R&uuml;cksetzen';
	
	$lang['notes'] = 'Notizen';
	$lang['addNote'] = 'Neue Notiz hinzuf&uuml;gen';
	
	$lang['orderby'] = 'Sortieren nach';
	$lang['ascending'] = 'Aufsteigend';
	$lang['descending'] = 'Absteigend';
	
	$lang['goto'] = 'Gehe zu';
	$lang['search_for'] = 'Suchbegriff';
	
	$lang['mail_notify_assignment_subject'] = 'Bugtracker: Zuweisung';
	$lang['mail_notify_assignment_text'] = '<p>Dir wurde eine Aufgabe im Bugtracker zugewiesen.</p>
		<p>ID: {$issueid}<br />Projekt: {$project}</br />Zusammenfassung: {$summary}</p>
		<p><a href="{$url}">Zum Fall</p>';
	
	$lang['remove_confirm'] = 'Soll der Eintrag wirklich entfernt werden?';
	$lang['cleanup'] = 'Aufr&auml;umen';
	$lang['remove_closed'] = 'Geschlossene Eintr&auml;ge entfernen';
	$lang['remove_closed_done'] = 'Geschlossene Eintr&auml;ge erfolgreich entfernt.';
?>