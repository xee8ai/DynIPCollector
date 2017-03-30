<?php

class Log {

	protected $levels = [
		'debug',
		'info',
		'warn',
		'error',
	];

	public function __construct($subdir='global', $lvl='error', $logging_enabled=True) {

		if (php_sapi_name() == 'cli') {
			$base_dir = realpath(dirname(__FILE__));
		}
		else {
			$base_dir = '.';
		}

		$this->logging_enabled = $logging_enabled;
		$this->lvl = $lvl;

		if ($this->logging_enabled) {
			$this->logdir = $base_dir.'/log/'.$subdir;
			if (!is_dir($this->logdir)) {
				mkdir($this->logdir, 0750, true);
			}
			if (!is_file($this->logdir."/index.htm")) {
				touch ($this->logdir."/index.htm");
			}
		}
	}

	public function debug($msg) {

		if (!in_array($this->lvl, ['debug'])) {
			return;
		}

		$this->_do_log('DEBUG:   '.$msg);
	}

	public function info($msg) {

		if (!in_array($this->lvl, ['debug', 'info'])) {
			return;
		}

		$this->_do_log('INFO:    '.$msg);
	}

	public function warn($msg) {

		if (!in_array($this->lvl, ['debug', 'info', 'warn'])) {
			return;
		}

		$this->_do_log('WARNING: '.$msg);
	}

	public function error($msg) {

		if (!in_array($this->lvl, ['debug', 'info', 'warn', 'error'])) {
			return;
		}

		$this->_do_log('ERROR:   '.$msg);
	}

	protected function _do_log($msg) {

		if ($this->logging_enabled) {
			$datestr = date('c');
			$line = $datestr." ".$msg."\n";
			$logfile = $this->logdir."/".substr($datestr, 0, 7).".log";
			file_put_contents($logfile, $line, FILE_APPEND);
		}
	}
}
