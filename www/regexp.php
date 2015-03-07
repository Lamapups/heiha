<?php

// defining some regular expressions
$ascii_regexp= '[ -~]';
$unicode_regexp = '[ẞſßÆæØøÐðÞþŊŋĦħ̣̣ŁłđçÄäËëÏïÖöÜüÁáÉéÍíÓóÚúÀàÈèÌìÒòÙù ÂâÊêÎîÔôÛûǍǎĚěǏǐǑǒǓǔĂăĔĕĬĭŎŏŬŭ’„‚“‘”’…÷–—]';
$unicode_ascii_regexp = '('.$ascii_regexp.'|'.$unicode_regexp.')';
//$unicode_regexp = '[\p{L}\p{N}\p{M}\p{S}\p{P}]';
//$unicode_regexp = '[\X]';

// regexp for a date in the form YYYY-MM-DD
$date_regexp = '(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))';

// options for FILTER_VALIDATE_REGEXP
$regexp_options = array(
	'regexp_username'	=> array('options' => array('regexp' => '/^'.$unicode_ascii_regexp.'{5,50}$/')),
	'regexp_password'	=> array('options' => array('regexp' => '/^'.$unicode_ascii_regexp.'{8,50}$/')),
	'regexp_forename'	=> array('options' => array('regexp' => '/^'.$unicode_ascii_regexp.'{0,50}$/')),
	'regexp_surname'	=> array('options' => array('regexp' => '/^'.$unicode_ascii_regexp.'{0,50}$/')),
	'regexp_university'	=> array('options' => array('regexp' => '/^'.$unicode_ascii_regexp.'{0,50}$/')),
	'regexp_title'		=> array('options' => array('regexp' => '/^[^\C\Z]{0,255}$/')),
	'regexp_subtitle'	=> array('options' => array('regexp' => '/^[\X]{0,255}$/')),
	'regexp_date'		=> array('options' => array('regexp' => '/'.$date_regexp.'/')),
	);

?>
