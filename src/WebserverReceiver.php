<?php

require_once('DynIPCollectorBase.php');

class WebserverReceiver extends DynIPCollectorBase {

	public function run() {

		$this->data_file = 'data/'.$this->cur_env['host'].'.ip';

		$this->_create_data();

		$this->ip = $_SERVER['REMOTE_ADDR'];

		$this->_show_ip();
		$this->_store_ip();
	}

	protected function _create_data() {

		$this->data = [];
		$this->data['secret'] = $this->config['secret'];

		if (!array_key_exists('time', $_GET)) {
			$msg = "Time not given";
			$this->log->error($msg);
			echo $msg;
			exit(1);
		}
		$this->data['timestamp'] = $_GET['time'];

		if (!array_key_exists('hash', $_GET)) {
			$msg = "Hash not given";
			$this->log->error($msg);
			echo $msg;
			exit(1);
		}
		$this->data['hash'] = $_GET['hash'];

		$this->data['host'] = $this->cur_env['host'];
	}

	protected function _show_ip() {
		echo $this->ip;
	}

	protected function _store_ip() {

		if ($this->_validate_data()) {
			file_put_contents($this->data_file, $this->data['timestamp']." ".$this->ip."\n");
		}

	}

	protected function _validate_data() {

		// store only if given timestamp is bigger than the last one to prevent URL replaying
		if (is_file($this->data_file)) {
			$last_time = explode(" ", file_get_contents($this->data_file));
			if ((int) $last_time >= $this->data['timestamp']) {
				$msg = "Timestamp to small";
				$this->log->error($msg);
				echo $msg;
				return False;
			}
		}

		// don't store if given hash is not valid – only verified clients are allowed to store IPs
		if (!$this->_verify_hash()) {
			$msg = "Invalid hash";
			$this->log->error($msg);
			echo $msg;
			return False;
		}

		return true;
	}

}

