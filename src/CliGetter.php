<?php

require_once('CliBase.php');

class CliGetter extends CliBase {

	public function run() {

		$this->_create_data();

		$webserver_return = $this->_perform_curl_request();

		$this->log->info('Returned data: '.$webserver_return);

		$ip = explode(' ', $webserver_return)[0];
		echo $ip;

	}

}

