<?php

class Categories extends Model {

	public function create($category) {
		$this->db->write("INSERT INTO video_categories (category) VALUES (?)", "s", $category);

		$response = new Response();
		if (!strlen($this->db->getError())) {
			$response->write("Kategoria " . $category . " bola vytvorena");
		} else {
			$response->error($this->db->getError());
		}

		return $response;
	}

	public function update($id, $category) {
		$this->db->write("UPDATE video_categories SET category = ? WHERE id = ?", "si", $category, $id);
	}

	public function get() {
		return $this->db->read("SELECT * FROM video_categories");
	}

	public function delete($id) {

		$response = new Response();
		$videos = new Videos();

		if ($videos->getAll(1, $id) > 0) {
			$response->error("Nemozno odstranit kategoriu ktora obsahuje videa");
		} else {
			$this->db->write("DELETE FROM video_categories WHERE id = ?", "i", $id);
			$response->write("Kategoria bola odstranena");
		}

		return $response;
	}
}