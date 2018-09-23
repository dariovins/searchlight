<?php
$PATH_TO_FILES = "files/";
$PATH_TO_SPEC_FILES = "specFiles";

$BASE_URI = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?';

$MEMBERS_TYPE = array(
1 => 'Administrators',
2 => 'Managers',
3 => 'Monitors',
4 => 'Members',
5 => 'Authors',
);

$STATUS = array(
0=> 'Confirm',
1 => 'Public',
2 => 'Private',
);

$EVENTS_TYPE = array (
		      );

// mapping labels for $_GET['cat']
// this is only for Control Panel
$CAT_TYPE = array (
		    'parliament'=>'Menus',
		    'mandate'=>'Sections',
		    'session'=>'Articles',
		    'minutes'=>'Pages',
		    'docs'=>'Documents',
		    'facts'=>'Facts',
		    //		    'links'=>'Links',
		    'news'=>'News',
		    'partners'=>'Partners',
		    'users'=>'Users',
		    );

// TYPE  db field tip =- EVENTS or ARTICLES
$EVENTS_TYPE = array (

1 => 'Minutes Agenda Debate',
2 => 'Voting',
);

$CONTENTS_TYPE = array( // always doc type
1 => 'Page',
);

$DOCS_TYPE = array( // always doc type
1 => 'Report',
2 => 'Picture',
3 => 'Presentation',
4 => 'Agenda',
5 => 'Handout',
6 => 'Attendees (list)',
7 => 'Misc',
);

$NEWS_TYPE = array(
1 => "Website News",
2 => 'Other News',
);

// TOPICS

// EVENT = Progress Meter
$EVENTS_TOPICS = array( // fixed topics - submenus for each EVENT -- idu u database kasnije, treba vidjeti kako adi basel II multiMenu
	       // moze biti chapters in EU Aciton Plan
1 => 'Partnership',
2 => 'EU Acqui Chapter Negotiations',
3 => 'European Standards',
//4 => '',
);

$CONTENTS_TOPICS = array( // fixed topics - submenus for each SADRZAJ -- idu u database kasnije, treba vidjeti kako adi basel II multiMenu
// moze biti chapters in EU Aciton Plan
1 => 'Political Criteria',
2 => 'Economic Criteria',
3 => 'European Standards',
//4 => '',
);
?>