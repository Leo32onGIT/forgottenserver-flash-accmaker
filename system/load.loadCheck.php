<?php
if(!defined('INITIALIZED'))
	exit;

if(file_exists('disable.txt'))
	die(file_get_contents('disable.txt'));

if(file_exists('install.txt'))
	{
        $main_content = '<a class="btn btn-primary" href="./install.php">Install Bootstrap AAC</a>';
        include_once('./game.php');
    };