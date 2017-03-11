<?php

require_once('etc/config.php');

// get the context (server, cli)

/* var_dump($_SERVER); */
if (php_sapi_name() == 'cli') {
	$context = 'cli';
}
else {
	$context = 'webserver';
}

if ($context == 'cli') {

	// the hosts we collect IPs for are the keys in config array
	$available_hosts = array_keys($config);

	// check if correct number of cli args has been given
	// if not: show usage string
	if (count($argv) != 3) {
		echo "Usage: $argv[0] host get|set\n";
		if (count($available_hosts) == 0) {
			echo "	currently there are no hosts configured";
		}
		elseif (count($available_hosts) == 1) {
			echo "	only one host configured: ".$available_hosts[0];
		}
		else {
			echo "	with host in [".implode('|', array_keys($config))."]";
		}
		echo "\n";
		exit(1);
	}

	// check if the given host is known (=configured in config.php)
	if (!in_array($argv[1], $available_hosts)) {
		echo "host $argv[1] unknown";
		echo "\n";
		exit(1);
	}

	// check if requested method is allowed
	if (!in_array($argv[2], ['set', 'get'])) {
		echo "method $argv[2] unknown";
		echo "\n";
		exit(1);
	}

}

// get the correct class file and instantiate it
if (
	($context == 'cli')
	&&
	($method == 'set')
) {
	require_once('src/CliSetter.php');
	$class = CliSetter();
	// start the command line setter
}
elseif (
	($context == 'cli')
	&&
	($method == 'get')
) {
	require_once('src/CliGetter.php');
	$class = CliGetter();
	// start the command line getter
}
elseif (
	($context == 'webserver')
	&&
	($method == 'set')
) {
	// start the webserver setter
	require_once('src/WebserverSetter.php');
	$class = WebserverSetter();
}
elseif (
	($context == 'webserver')
	&&
	($method == 'src/WebserverGetter.php')
) {
	// start the webserver getter
	$class = WebserverGetter();
}
else {
	exit(1);
}

$class->fire();
