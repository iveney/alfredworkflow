<?php

function cmp($a, $b) {
    return $b->thumbs_up - $a->thumbs_up;
}

require_once('workflows.php');
$w = new Workflows();

// $term = "{query}";
$term = "FYI";
$url = 'http://api.urbandictionary.com/v0/define?term='.urlencode($term);
$result = json_decode($w->request($url));

$num = count ($result->list);
if ($num != 0) {
	// sort by thumbs-ups
	$defs = $result->list;
	usort($defs, 'cmp');
	foreach( $defs as $def ) {
		// thumbs up/down not yet in font
		$w->result($def->defid,
				   $def->permalink,
				   $def->definition,
				   '▲ '.$def->thumbs_up. ' ▼ '.$def->thumbs_down.
				   		' | '.$def->example,
				   'icon.png',
				   'yes');
	}
}
else {
	$w->result('urbandictionary',
			   'http://www.google.com/search?q='.$term,
			   "Can't find a definition for ".$term,
			   "Press enter to search in google",
			   'icon.png',
			   'yes');
}

echo $w->toxml();

?>