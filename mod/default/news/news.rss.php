<?php
	if ($config->get('news', 'rss') == '1') {
		$newslist = $db->selectList('news', '*', '1', '`timestamp` DESC');
		$h = fopen("./media/rss/news.rss", "w");
		fwrite($h, '<?xml version="1.0" encoding="utf-8"?>');
		fwrite($h, '<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">');
		fwrite($h, '<channel>');
		fwrite($h, '<atom:link href="'.getSelfURL().'/media/rss/news.rss" type="application/rss+xml" />');
		fwrite($h, '<title>'.$config->get('core', 'title').'</title>');
		fwrite($h, '<link>'.getSelfURL().'</link>');
		fwrite($h, '<description></description>');
		fwrite($h, '<language>'.$config->get('core', 'language').'</language>');
		fwrite($h, '<copyright></copyright>');
		fwrite($h, '<pubDate>'.date('D, d M Y H:i:s O').'</pubDate>');
		foreach ($newslist as $item) {
			$u = $db->selectOneRow('users', 'email, nickname', "`userid`=".$item['userid']);
			$url = getSelfURL().'/'.makeURL($mod, array('newsid' => $item['newsid']));
			fwrite($h, '<item>');
			fwrite($h, '<title>'.$item['title'].'</title>');
			fwrite($h, '<description>'.replaceXmlEntities($bbcode->parse($item['text'])).'</description>');
			fwrite($h, '<link>'.$url.'</link>');
			fwrite($h, '<author>'.$u['email'].' ('.$u['nickname'].')'.'</author>');
			fwrite($h, '<guid>'.$url.'</guid>');
			fwrite($h, '<pubDate>'.date('D, d M Y H:i:s O', $item['timestamp']).'</pubDate>');
			fwrite($h, '</item>');
		}
		fwrite($h, '</channel>');
		fwrite($h, '</rss>');
		fclose($h);
	}
?>