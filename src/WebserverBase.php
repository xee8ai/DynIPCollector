<?php

require_once('DynIPCollectorBase.php');

class WebserverBase extends DynIPCollectorBase {

	protected function _create_data() {

		$this->data = [];
		$this->data['secret'] = $this->config['secret'];

		if (!array_key_exists('time', $_GET)) {
			$msg = "Time not given";
			$this->log->error($msg);
			echo $msg;
			return false;
		}
		$this->data['timestamp'] = $_GET['time'];

		if (!array_key_exists('hash', $_GET)) {
			$msg = "Hash not given";
			$this->log->error($msg);
			echo $msg;
			return false;
		}
		$this->data['hash'] = $_GET['hash'];

		$this->data['host'] = $this->cur_env['host'];

		return true;
	}

}
