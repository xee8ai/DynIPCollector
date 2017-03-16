<?php

require_once('DynIPCollectorBase.php');

class CliBase extends DynIPCollectorBase {

	/**
	 * Generates the data used for creating hash and url
	 */
	protected function _create_data() {

		$this->data = [];
		$this->data['secret'] = $this->config['secret'];
		$this->data['timestamp'] = time();
		$this->data['host'] = $this->cur_env['host'];
		$this->data['hash'] = $this->_create_hash();
		$this->data['url'] = $this->_create_url();

	}

	/**
	 * Create the URL to be visited
	 */
	protected function _create_url() {

		$params_raw = array(
			"host" => $this->data['host'],
			"method" => $this->cur_env['method'],
			"time" => $this->data['timestamp'],
			"hash" => $this->data['hash'],
		);

		$params_url_ready = [];
		foreach ($params_raw as $k => $v) {
			array_push($params_url_ready, $k."=".urlencode($v));
		}

		$this->data['encoded_url'] = $this->config['url'].'?'.implode('&', $params_url_ready);

	}

}


