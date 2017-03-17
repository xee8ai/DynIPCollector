<?php

require_once('WebserverBase.php');

class WebserverReceiver extends WebserverBase {

	public function run() {

		$this->data_file = 'data/'.$this->cur_env['host'].'.php';

		if (!$this->_create_data()) {
			return false;
		}

		$this->ip = $_SERVER['REMOTE_ADDR'];

		$this->_show_ip();
		$this->_store_ip();
	}


	protected function _show_ip() {
		echo $this->ip;
	}

	protected function _store_ip() {

		if ($this->_validate_data()) {
			$content = "<?php\n\$ip_content = ['time'=>".$this->data['timestamp'].", 'ip'=>'".$this->ip."'];\n";
			file_put_contents($this->data_file, $content);
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

