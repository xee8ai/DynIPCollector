<?php

require_once('CliBase.php');

class CliGetter extends CliBase {

	public function run() {

		$this->_create_data();
		var_dump($this->data);

		$webserver_return = $this->_perform_curl_request();

		echo $webserver_return;

	}

}

