<?php

class IndexViewController extends ViewController {
	
	function __construct() {
		$this->paths = [
			"index" => "Index",
			"about" => "About",
			"contact" => "Contact",
			"video-page" => "Video",
		];

		// prihlasit sa da odkialkolvek na stranke, spravime to pred volbou metody
		if (isset($_POST["action"]) && !strcmp($_POST["action"], "subscribe") && isset($_POST["email"])) {
			$newsletter = new Newsletter();
			$this->response = $newsletter->subscribe($_POST["email"]);
		}
	}

	function Index() {
		$categories = new Categories();
		$this->data["categories"] = $categories->get();

		if (!isset($_GET["category"]) || !is_numeric($_GET["category"]))
			$_GET["category"] = -1;

		if (!isset($_GET["page"]) || !is_numeric($_GET["page"]) || $_GET["page"] < 1)
			$_GET["page"] = 1;

		$videos = new Videos();
		$this->data["videos"] = $videos->getAll($_GET["page"], $_GET["category"]);
	}

	function Video() {

		if (!isset($_GET["video"]))
			header("Location: /");

		$videos = new Videos();

		if (isset($_GET["action"])) {
			if ($_GET["action"] == "like") {
				$this->response = $videos->like($_GET["video"]);
			}
		}

		$this->data["video"] = $videos->get($_GET["video"]);

		if (count($this->data["video"]) == 0) {
			header("Location: /");
		}

		$this->data["recommended_videos"] = $videos->getRecommended();
		$this->data["parallax_header"] = "tm-fixed-header-1";
		$this->data["parallax_title"] = $this->data["video"]["title"];
	}

	function Contact() {
		$this->data["parallax_header"] = "tm-fixed-header-3";
		$this->data["parallax_title"] = "Talk to Us<br>about any question you have";

		$email = new Email();
		$this->data["email_categories"] = $email->getCategories();
		if (isset($_POST["action"]) && !strcmp($_POST["action"], "send_email")) {

			$response = new Response();
			if (empty($_POST["sender_name"])) {
				$response->error("Meno odosielatela nemoze byt prazdne");
			}

			if (empty($_POST["sender_email"]) || !filter_var($_POST["sender_email"], FILTER_VALIDATE_EMAIL)) {
				$response->error("E-mail nie je platny");
			}

			if (!isset($_POST["subject"])) {
				$response->error("Predmet nie je platny");
			} else if ($_POST["subject"] == -1) {
				$response->error("Nezvolili ste predmet spravy");
			}

			if (!isset($_POST["message"])) {
				$response->error("Sprava nemoze byt prazdna");
			}

			if ($response->getErrorCount() == 0)
				$this->response = $email->send($_POST["sender_name"], $_POST["sender_email"], $_POST["subject"], $_POST["message"]);
			else
				$this->response = $response;
		}
	}

	function About() {
		$this->data["parallax_header"] = "tm-fixed-header-2";
		$this->data["parallax_title"] = "Another Image BG<br>it can be fixed.<br>Content will simply slide over.";
	}
}