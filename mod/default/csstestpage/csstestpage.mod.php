<?php
$notify->add('Notification', 'Lorem ipsum dolor sit amet. Und noch viel anderer Text damit wir einen Zeilenumbruch erreichen, das wird ganz sch&ouml;n schwer werden, wenn die Seite breit ist, was kann man da blo&szlig; machen, aber jetzt haben wir es gleich, man sind wir gut.');
$notify->add('Notification', 'Noch eine Notification, um zu sehen wie es aussieht wenn zwei gleichzeitig auftauchen.');
$breadcrumbs->addElement('breadcrumb0', '#');
$breadcrumbs->addElement('breadcrumb1', '#');
$breadcrumbs->addElement('breadcrumb2', '');
$smarty->assign('path', $template_dir."/testpage.tpl");
?>