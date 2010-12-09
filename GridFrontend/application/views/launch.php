<?php

$launch_doc = NULL;

if ($type === 'capability') {
    $launch_doc = "{ \"authenticator\": { \"type\": \"capability\" }, \"identifier\": { \"type\": \"agent\", \"name\": \"$name\" }, \"loginurl\": \"$login_url\", \"region\": \"$region\" }";
} else {
    $launch_doc = "{ \"loginurl\": \"$login_url\", \"region\": \"$region\" }";
}

// Mark this document as no-cache
header("Expires: Mon, 20 Dec 1998 01:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");

// Set the launch document MIME type
header('Content-type: application/calm+json');

echo $launch_doc;
