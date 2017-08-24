<?php

// Argument parsing
if ($argc !== 3) {
	die('Missing stream or version. Proper commmand arguments is: php build-version.php <branch> <version>');
}

$stream = $argv[1];
$version = $argv[2];

// Template
$ifTravis = "// This file has been auto built by Travis-CI on " . date('d-M-Y g:i:s:A');
$out = <<<EOF
 <?php
{{IF-TRAVIS}}

// This defines which branch this version of solder is using
if(!defined('SOLDER_STREAM'))
	define('SOLDER_STREAM', '{{STREAM}}');

// This defines which version of solder is being built
if(!defined('SOLDER_VERSION'))
	define('SOLDER_VERSION', '{{VERSION}}');

EOF;


// Formatting
$travisEnv = getenv('TRAVIS');
if (strtolower($travisEnv) == "true")
	$formatted = str_replace('{{IF-TRAVIS}}', $ifTravis, $out);
else
	$formatted = str_replace('{{IF-TRAVIS}}', '', $out);

$formatted = str_replace('{{STREAM}}', $stream, $formatted);
$formatted = str_replace('{{VERSION}}', $version, $formatted);

echo($formatted);

// Saving to file
$outputFile = getenv('OUTPUT');
$outputFile = $outputFile !== false ? $outputFile : "version.php";
if ($outputFile != "") {
	file_put_contents($outputFile, $formatted);
}