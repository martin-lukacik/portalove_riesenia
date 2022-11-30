<?php

class ViewController {

	public $data = Array();
	public $response = null;

	public $paths = Array();

	public function Route() {

		global $config;

		if (!isset($_GET["route"])) {
			$_GET["route"] = "index";
		}

		if (!array_key_exists($_GET["route"], $this->paths)) {
		    header("Location: " . $config["url"]);
		}

		echo call_user_func(array($this, $this->paths[$_GET["route"]]));
	}

	public function printResponse() {

		if ($this->response == null || $this->response instanceof Response)
			return;

		if (!is_null($this->response->failure) && count($this->response->failure) > 0) {
			?><div class="alert alert-danger">
				<h4>Chyba</h4>
				<? foreach ($this->response->failure as $failure) {
					?><li><?=$failure?></li><?
				} ?>
			</div><?
		} else if (!is_null($this->response->success) && count($this->response->success) > 0) {
			?><div class="alert alert-success">
				<h4>Uspech</h4>
				<? foreach ($this->response->success as $success) {
					?><li><?=$success?></li><?
				} ?>
			</div><?
		}
	}
}