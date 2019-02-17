<?php
$link = mysqli_connect('localhost', 'root', 'root', 'doingsdone');
mysqli_set_charset($link, 'utf8');

$categories = [];
$tasks = [];
