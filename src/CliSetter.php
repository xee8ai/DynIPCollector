<?php

require_once('DynIPCollectorBase.php');

class CliSetter extends DynIPCollectorBase {

	public function run() {

		$this->_create_data();

		$webserver_return = $this->_perform_curl_request();

		echo $webserver_return;

	}

	protected function _create_data() {

		$this->log->error('test');
		$this->data = [];
		$this->data['secret'] = $this->config['secret'];
		$this->data['timestamp'] = time();
		$this->data['host'] = $this->cur_env['host'];

		$this->data['hash'] = $this->_create_hash();

		$this->data['url'] = $this->_create_url();

	}

	protected function _create_url() {

		$params = "?host=".$this->data['host']."&method=set&time=".$this->data['timestamp']."&hash=".$this->data['hash'];
		$encoded_params = "?host=".urlencode($this->data['host'])."&method=set&time=".urlencode($this->data['timestamp'])."&hash=".urlencode($this->data['hash']);
		$this->data['url'] = $this->config['url'].$params;
		$this->data['encoded_url'] = $this->config['url'].$encoded_params;

	}

}

