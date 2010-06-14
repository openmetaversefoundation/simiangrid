<?php

$launch_doc = NULL;

if (isset($name))
{
    if (isset($region))
    {
        $launch_doc = "{ \"authenticator\": { \"type\": \"capability\" }, \"identifier\": " +
                     "{ \"type\": \"agent\", \"name\": \"$name\" }, \"loginurl\": \"$login_url\", " +
                     "\"region\": \"$region\" }";
    }
    else
    {
        $launch_doc = "{ \"authenticator\": { \"type\": \"capability\" }, \"identifier\": " +
                     "{ \"type\": \"agent\", \"name\": \"$name\" }, \"loginurl\": \"$login_url\" }";
    }
}
else
{
    $launch_doc = "{ \"loginurl\": \"$login_url\" }";
}

echo($launch_doc);
