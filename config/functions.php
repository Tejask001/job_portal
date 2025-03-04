<?php
// config/functions.php

function redirect($url)
{
    header("Location: " . $url);
    exit();
}
