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
		return $this->db->read("SELECT v.*, a.name AS author, a.url AS author_url, (SELECT COUNT(l.video_id) FROM video_likes l WHERE l.video_id = ?) AS likes FROM videos v INNER JOIN video_authors a ON v.author_id = a.id WHERE v.id = ? LIMIT 1", "ii", $id, $id);
	}

	public function like($id) {

		$response = new Response();

		if ($this->isLikedVideo($id)) {
			$this->db->write("DELETE FROM video_likes WHERE video_id = ? AND ip = ?", "is", $id, $_SERVER["REMOTE_ADDR"]);
			return $response;
		} else if ($this->videoExists($id) == false) {
			$response->error("Video neexistuje");
			return $response;
		}

		$this->db->write("INSERT INTO video_likes (video_id, ip) VALUES (?, ?)", "is", $id, $_SERVER["REMOTE_ADDR"]);

		return $response;
	}

	public function videoExists($id) {
		$this->db->read("SELECT id FROM videos WHERE id = ? LIMIT 1", "i", $id);

		return $this->db->lastRows() > 0;
	}

	public function isLikedVideo($id) {
		$this->db->read("SELECT id FROM video_likes WHERE video_id = ? AND ip = ?", "is", $id, $_SERVER["REMOTE_ADDR"]);

		return $this->db->lastRows() > 0;
	}

	public function create($title, $description, $author, $category, $url, $thumbnail) {

		$response = new Response();

		$authors = new Authors();

		if (count($authors->get($author)) == 0) {
			$response->error("Autor neexistuje");
			return $response;
		}

		$this->db->write("INSERT INTO videos (title, description, author_id, category_id, url, thumbnail_url) VALUES (?, ?, ?, ?, ?, ?)", "ssiiss", $title, $description, $author, $category, $url, $thumbnail);

		if (strlen($this->db->getError())) {
			$response->error($this->db->getError());
			return $response;
		}

		return $this->db->getLastID();
	}

	public function update($id, $title, $description, $author, $category, $url, $thumbnail) {

		$response = new Response();

		$this->db->read("SELECT id FROM videos WHERE id = ?", "i", $id);

		if ($this->db->lastRows() == 0) {
			$response->error("Video neexistuje");
			return $response;
		}

		$this->db->write("UPDATE videos SET title = ?, description = ?, author_id = ?, category_id = ?, url = ?, thumbnail_url = ? WHERE id = ?", "ssiissi", $title, $description, $author, $category, $url, $thumbnail, $id);
	
		if (strlen($this->db->getError())) {
			$response->error($this->db->getError());
		} else {
			$response->write("Zmeny boli ulozene");
		}

		return $response;
	}

	public function delete($id) {
		$this->db->write("DELETE FROM video_likes WHERE video_id = ?", "i", $id);
		$this->db->write("DELETE FROM videos WHERE id = ?", "i", $id);

		$response = new Response();
		if ($this->db->lastRows() > 0) {
			$response->write("Video (ID: " . $id . ") bolo odstranene");
		} else {
			$response->error("Video neexistuje");
		}

		return $response;
	}
}