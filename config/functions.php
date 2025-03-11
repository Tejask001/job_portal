<?php
// config/functions.php

define('JOBS_PER_PAGE', 10); // Number of jobs to display per page
define('PAGINATION_LINKS', 5);  // Number of pagination links to show (e.g., 1 2 3 4 5)

function redirect($url)
{
    header("Location: " . $url);
    exit();
}
