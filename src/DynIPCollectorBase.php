<?php

class DynIPCollectorBase {

	public function __construct($config, $cur_env, $log) {

		$this->config = $config[$cur_env['host']];
		$this->cur_env = $cur_env;
		$this->log = $log;

		if (!is_dir('data')) {
			mkdir('data', 0750, true);
		}
		if (!is_file("data/index.htm")) {
			touch ("data/index.htm");
		}
	}


	protected function _create_string_to_hash() {

		$data_string = $this->data['host']."__".$this->data['timestamp']."__".$this->data['secret'];

		return $data_string;
	}


	protected function _create_hash() {

		$data_to_hash = $this->_create_string_to_hash();

		$hash = password_hash($data_to_hash, PASSWORD_BCRYPT);

		return $hash;
	}

	protected function _verify_hash() {

		$data_to_hash = $this->_create_string_to_hash();

		return password_verify($data_to_hash, $this->data['hash']);
	}


	protected function _perform_curl_request() {

		$ch = curl_init();

		$opts = array(
			CURLOPT_URL => $this->data['encoded_url'],
			CURLOPT_HEADER => false,
			CURLOPT_SSL_VERIFYPEER => false,    // no valid cert for “localhost” – so we don't check
			CURLOPT_RETURNTRANSFER => TRUE,     // return result instead of instantly printing to screen
		);

		curl_setopt_array($ch, $opts);

		$res = curl_exec($ch);
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		if ($http_code != 200) {
			echo "HTTP error: $http_code";
		}

		curl_close($ch);

		return $res;
	}

}
