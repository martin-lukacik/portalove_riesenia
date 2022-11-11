<?php

class Model {

	protected $db = null;

	function __construct() {
		global $config;
		$this->db = new Database($config["db"]);
	}
}