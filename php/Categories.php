<?php

class Categories extends Model {

	public function get() {
		return $this->db->read("SELECT * FROM video_categories");
	}
}