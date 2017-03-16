<?php

// this is only an configuration example
// please write your own etc/config.php

// boolean value to enable/disable logging
$conf_logging_enabled = True;

// min log level (debug|info|warn|error)
$conf_log_level = 'error';

// configuration of all known hosts
$config = array(

	'your_host1_abbrev' => array(

		'desc' => 'Host 1 description',
		'secret' => 'your_host1_shared_secret',
		'url' => 'your_webserver_url',
	),
	'your_host1_abbrev' => array(

		'desc' => 'Host 1 description',
		'secret' => 'your_host1_shared_secret',
		'url' => 'your_webserver_url',
	),

);
