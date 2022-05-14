<?php

//Close user session
require_once 'init.php';

$_SESSION['user'] = [];
header("Location: index.php");
