<?php

class Response {
	public $success = Array();
	public $failure = Array();

	function write($message) {
		array_push($this->success, $message);
	}

	function error($message) {
		array_push($this->failure, $message);
	}
}