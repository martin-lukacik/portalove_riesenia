<?php

class Response {
	public $success = [];
	public $failure = [];

	function write($message) {
		$this->success[] = $message;
	}

	function error($message) {
		$this->failure[] = $message;
	}

	function getMessageCount() {
		return count($this->success);
	}

	function getErrorCount() {
		return count($this->failure);
	}
}