<?php

class Email extends Model {

	public function send($sender, $email, $subject, $message) {
		
		$response = new Response();
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$response->error("Uvedeny e-mail nie je platny");
			return $response;
		}

		$this->db->write("INSERT INTO emails (sender_name, sender_email, message, category_id) VALUES (?, ?, ?, ?)", "sssi", $sender, $email, $message, $subject);

		$response->write("Sprava bola uspesne odoslana");

		return $response;
	}

	public function markAsRead($id) {
		$this->db->write("UPDATE emails SET read_date = NOW() WHERE id = ?", "i", $id);
	}

	public function markAsUnread($id) {
		$this->db->write("UPDATE emails SET read_date = NULL WHERE id = ?", "i", $id);
	}

	public function delete($id) {
		$this->db->write("DELETE FROM emails WHERE id = ?", "i", $id);

		$response = new Response();
		if ($this->db->lastRows() > 0) {
			$response->write("E-mail bol odstraneny");
		} else {
			$response->error("E-mail neexistuje");
		}

		return $response;
	}
}