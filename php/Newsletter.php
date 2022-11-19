<?php

class Newsletter extends Model {

	function subscribe($email) {

		$response = new Response();

		if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$this->db->read("SELECT id FROM newsletter WHERE email = ?", "s", $email);

			if ($this->db->lastRows() > 0) {
				//$response->error("Tento e-mail je uz prihlaseny na odber");
				return $this->unsubscribe($email);
			} else {
				$this->db->write("INSERT INTO newsletter (email) VALUES (?)", "s", $email);
				$response->write("Uspesne prihlaseny na odber");
			}
		} else {
			$response->error("Uvedeny e-mail nie je platny");
		}

		return $response;
	}

	function unsubscribe($email) {

		$this->db->write("DELETE FROM newsletter WHERE email = ?", "s", $email);

		$response = new Response();

		if ($this->db->lastRows() > 0) {
			$response->write("E-mail " . $email . " bol odhlaseny z odberu");
		} else {
			$response->error("Uvedeny e-mail nie je prihlaseny na odber");
		}

		return $response;
	}
}