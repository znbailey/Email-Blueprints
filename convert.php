<?php
	if (!isset($argv[1])) {
		echo "usage: php convert.php <template filename>\n";
		echo "example: `find . -type f -exec php convert.php {} \;`\n";
		exit;
	}
	
	$template = file_get_contents($argv[1]);
	
	$replacements = array(
		'\*\|MC:SUBJECT\|\*' => '%%subject%%',
		'\*\|ARCHIVE\|\*' => '%%view_online%%',
		'\*\|TWITTER:PROFILEURL\|\*' => '#',
		'\*\|FACEBOOK:PROFILEURL\|\*' => '#',
		'\*\|END:IF\|\*' => '',
		'\*\|IF:\w+?\|\*' => '',
		'\*\|IFNOT:\w+?\|\*' => '',
		'\*\|CURRENT_YEAR\|\*' => '2012',
		'\*\|LIST:COMPANY\|\*' => '',
		'\*\|LIST:DESCRIPTION\|\*' => '',
		'\*\|HTML:LIST_ADDRESS_HTML\|\*' => '%%account_address%%',
		'\*\|UNSUB\|\*' => '%%unsubscribe%%',
		'\*\|UPDATE_PROFILE\|\*' => '%%email_preference_center%%',
		'\*\|FORWARD\|\*' => '%%addthis_url_email%%',
		'\*\|HTML:REWARDS\|\*' => '',
		//convert editable content blocks
		'mc:edit="(.+?)"' => 'pardot-region="\\1"',
		//convert repeatable content blocks
		'mc:repeatable' => 'pardot-repeatable',
		//remove mailchimp attributes WITH values
		' mc:\w+?=".+?"' => '',
		//remove mailchimp attributes WITHOUT values
		' mc:\w+' => '',
		'http:\/\/gallery.mailchimp.com\/653153ae841fd11de66ad181a\/images\/placeholder_600.gif' => 'http://placehold.it/600x150',
		'http:\/\/gallery.mailchimp.com\/653153ae841fd11de66ad181a\/images\/placeholder_160.gif' => 'http://placehold.it/160x160',
		'http:\/\/gallery.mailchimp.com\/653153ae841fd11de66ad181a\/images\/placeholder_260.gif' => 'http://placehold.it/260x200',
		'http:\/\/gallery.mailchimp.com\/653153ae841fd11de66ad181a\/images\/placeholder_110.gif' => 'http://placehold.it/110x110',
		'http:\/\/gallery.mailchimp.com\/653153ae841fd11de66ad181a\/images\/sfs_icon_forward.png' => 'https://pi.pardot.com/images/addthis/16x16/email.png',
		'http:\/\/gallery.mailchimp.com\/653153ae841fd11de66ad181a\/images\/sfs_icon_facebook.png' => 'https://pi.pardot.com/images/addthis/16x16/facebook.png',
		'http:\/\/gallery.mailchimp.com\/653153ae841fd11de66ad181a\/images\/sfs_icon_twitter.png' => 'https://pi.pardot.com/images/addthis/16x16/twitter.png',
		'<\/html>' => "    <!--\n      This email was originally designed by the wonderful folks at MailChimp and remixed by Pardot.\n      It is licensed under CC BY-SA 3.0 - http://creativecommons.org/licenses/by-sa/3.0/\n    -->\n</html>",
		'\/\*\*(.+?)\*\/' => '',
		'\/\*@(.+?)\*\/' => '',
	);
	
	foreach($replacements as $mcToken => $pdToken) {
		$template = preg_replace('/'.$mcToken.'/is', $pdToken, $template);
	}
	
	$fout = fopen('../converted/'.$argv[1], 'w');
	fwrite($fout, $template);
	fclose($fout);
	
	echo 'Done!';
	