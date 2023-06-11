<?php

$ds = DIRECTORY_SEPARATOR;


//cors.php
$corsFoundationPath = base_path(
    "Corals" . $ds . "core" . $ds . "Foundation" . $ds . "config" . $ds . "cors.php"
);
$publicCorsPath = base_path("config" . $ds . "cors.php");


file_put_contents(
    $publicCorsPath,
    file_get_contents($corsFoundationPath)
);


//Kernel.php

$KernelFoundationPath = base_path(
    "Corals" . $ds . "core" . $ds . "Foundation" . $ds . "Http" . $ds . "Kernel.php"
);

$publicKernelPath = base_path("app" . $ds . "Http" . $ds . "Kernel.php");

file_put_contents(
    $publicKernelPath,
    file_get_contents($KernelFoundationPath)
);
