<?php

class AdminViewController extends ViewController {

	function edit($id, $url, $thumbnail) {
		$videos = new Videos();
		return $videos->update($id, $_POST["title"], $_POST["description"], $_POST["author_id"], $_POST["category_id"], $url, $thumbnail);
	}

	function upload() {
		if (!isset($_FILES["file_video"]) || $_FILES["file_video"]["size"] == 0) {
			$this->response->error("Ziadne video nebolo poskytnute");
		}

		if (!isset($_FILES["file_image"]) || $_FILES["file_image"]["size"] == 0) {
			$this->response->error("Ziadny thumbnail nebol poskytnuty");
		}

		$time = time();

		// upload video
		$fileName = basename($_FILES["file_video"]["name"]);
		$targetName = $time . "." . strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
		$targetVideo = "./upload/" . $targetName;

		move_uploaded_file($_FILES["file_video"]["tmp_name"], $targetVideo);

		// upload image
		$fileName = basename($_FILES["file_image"]["name"]);
		$targetName = $time . "." . strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
		$targetImage = "./upload/" . $targetName;

		move_uploaded_file($_FILES["file_image"]["tmp_name"], $targetImage);

		// save
		$videos = new Videos();
		$this->response = $videos->create($_POST["title"], $_POST["description"], $_POST["author_id"], $_POST["category_id"], $targetVideo, $targetImage);
	
		if (is_numeric($this->response)) {
			$this->response = new Response();
			$this->response->write("Video " . $_POST["title"] . " bolo pridane");
		}
	}
}