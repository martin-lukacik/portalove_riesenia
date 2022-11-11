<?php

class Newsletter extends Model {

	function subscribe($email) {

		$response = new Response();

		$this->db->read("SELECT id FROM newsletter WHERE email = ?", "s", $email);

		if ($this->db->lastRows() > 0) {
			$response->error("Tento e-mail je uz prihlaseny na odber");
		} else {
			$this->db->write("INSERT INTO newsletter (email) VALUES (?)", "s", $email);
			$response->write("Uspesne prihlaseny na odber");
		}

		return $response;
	}
}