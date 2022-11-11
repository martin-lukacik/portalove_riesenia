<?php

class Videos extends Model {

	public function getAll($page, $category = -1) {

		$perPage = 9;
		$offset = ($page - 1) * $perPage;

		if ($category == -1)
			return $this->db->read("SELECT * FROM videos LIMIT ?, ?", "ii", $offset, $perPage);

		return $this->db->read("SELECT * FROM videos WHERE category_id = ? LIMIT ?, ?", "iii", $category, $offset, $perPage);
	}

	public function get($id) {
		return $this->db->read("SELECT * FROM videos WHERE id = ? LIMIT 1", "i", $id);
	}
}