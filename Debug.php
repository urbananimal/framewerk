<?php
function pr($param)
{
	echo '<pre style="border: 1px solid #CCC; padding: 10px;">';
	print_r($param);
	echo '</pre>';
}

function pre($param)
{
	pr($param);
	die();
}

function vd($foo) {
    echo '<pre style="border: 1px solid #CCC; padding: 10px;">';
	var_dump($foo);
	echo '</pre>';
}

function vde($foo) {
    vd($foo);
    exit;
}

function lpr($foo) {
    Log::logMessage(print_r($foo, true));
}
function lpre($foo) {
    lpr($foo);
    exit;
}
?>