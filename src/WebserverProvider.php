<?php

require_once('WebserverBase.php');

class WebserverProvider extends WebserverBase {

	public function run() {

		$this->data_file = 'data/'.$this->cur_env['host'].'.php';

		if (!$this->_create_data()) {
			return false;
		}

		if ($this->_verify_hash()) {
			require_once($this->data_file);
			echo $ip_content['ip'].' ('.(date('c', $ip_content['time'])).')';
		}
	}

}

