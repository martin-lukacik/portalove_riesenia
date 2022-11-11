<?php

include("./php/config.php");

$view = new IndexViewController();
$view->Route();

include("./php/template.phtml");