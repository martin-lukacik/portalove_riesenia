<?php

class IndexViewController extends ViewController {
	
	function __construct() {
		$this->paths = [
			"index" => "Index",
		];

		// prihlasit sa da odkialkolvek na stranke, spravime to pred volbou metody
		if (isset($_POST["action"]) && isset($_POST["email"]) && !strcmp($_POST["action"], "do_subscribe")) {
			$newsletter = new Newsletter();
			$this->response = $newsletter->subscribe($_POST["email"]);
		}
	}

	function Index() {
		$categories = new Categories();
		$this->data["categories"] = $categories->get();

		if (!isset($_GET["category"]))
			$_GET["category"] = -1;

		if (!isset($_GET["page"]))
			$_GET["page"] = 1;

		$videos = new Videos();
		$this->data["videos"] = $videos->getAll($_GET["page"], $_GET["category"]);
	}
}