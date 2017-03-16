<?php

require_once('etc/config.php');
require_once('logger.php');

$log = new Log('', $conf_log_level, $conf_logging_enabled);

$cur_env = array();

// get the context (server, cli)

/* var_dump($_SERVER); */
if (php_sapi_name() == 'cli') {
	$cur_env['context'] = 'cli';
}
else {
	$cur_env['context'] = 'webserver';
}

// the hosts we collect IPs for are the keys in config array
$available_hosts = array_keys($config);

if ($cur_env['context'] == 'cli') {

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
		$msg =  "Host $argv[1] unknown";
		$log->error($msg);
		echo $msg;
		echo "\n";
		exit(1);
	}
	else {
		$cur_env['host'] = $argv[1];
		$log = new Log($argv[1], $conf_log_level, $conf_logging_enabled);
	}

	// check if requested method is allowed
	if (!in_array($argv[2], ['set', 'get'])) {
		$msg =  "Method $argv[2] unknown";
		$log->error($msg);
		echo $msg;
		echo "\n";
		exit(1);
	}
	else {
		$cur_env['method'] = $argv[2];
	}
}
elseif ($cur_env['context'] == 'webserver') {

	// get the method
	if (array_key_exists('method', $_GET) && in_array($_GET['method'], ['set', 'get'])) {
		$cur_env['method'] = $_GET['method'];
	}
	else {
		$msg = "Missing or wrong method";
		$log->error($msg);
		echo $msg;
		exit(1);
	}

	// get the host
	if (array_key_exists('host', $_GET) && in_array($_GET['host'], $available_hosts)) {
		$cur_env['host'] = $_GET['host'];
	}
	else {
		$msg =  "Missing or wrong host";
		$log->error($msg);
		echo $msg;
		exit(1);
	}
}

// get the correct class file and instantiate it
if (
	($cur_env['context'] == 'cli')
	&&
	($cur_env['method'] == 'set')
) {
	require_once('src/CliSetter.php');
	$class = new CliSetter($config, $cur_env, $log);
	// start the command line setter
}
elseif (
	($cur_env['context'] == 'cli')
	&&
	($cur_env['method'] == 'get')
) {
	require_once('src/CliGetter.php');
	$class = new CliGetter($config, $cur_env, $log);
	// start the command line getter
}
elseif (
	($cur_env['context'] == 'webserver')
	&&
	($cur_env['method'] == 'set')
) {
	// start the webserver setter
	require_once('src/WebserverReceiver.php');
	$class = new WebserverReceiver($config, $cur_env, $log);
}
elseif (
	($cur_env['context'] == 'webserver')
	&&
	($cur_env['method'] == 'get')
) {
	// start the webserver getter
	require_once('src/WebserverProvider.php');
	$class = new WebserverProvider($config, $cur_env, $log);
}
else {
	exit(1);
}

$class->run();
