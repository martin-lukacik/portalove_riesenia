<?php

class Authors extends Model {

	public function create($author, $url) {
		$this->db->write("INSERT INTO video_authors (name, url) VALUES (?, ?)", "ss", $author, $url);

		return $this->db->getLastID();
	}

	public function get($id) {
		return $this->db->read("SELECT * FROM video_authors WHERE id = ? LIMIT 1", "i", $id);
	}

	public function getAll() {
		return $this->db->read("SELECT * FROM video_authors");
	}

	public function update($id, $author, $url) {
		throw new Exception("Not implemented", 1);
	}

	public function delete() {
		// TODO nemozno vymazat autora ak ma videa
		throw new Exception("Not implemented", 1);
	}
}