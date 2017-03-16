<?php

require_once('CliBase.php');

class CliSetter extends CliBase {

	public function run() {

		$this->_create_data();

		$webserver_return = $this->_perform_curl_request();

		echo $webserver_return;

	}

}
