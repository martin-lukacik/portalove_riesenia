<?php

$config = [

	"url" => "http://localhost",

	"db" => [
		"host" => "localhost",
		"user" => "root",
		"pass" => "",
		"name" => "video_catalog",
	],
];

spl_autoload_register(function ($class_name) { 
    include("./php/" . $class_name . ".php");
});