<?php

include 'vendor/autoload.php';

define('BASE_PATH', __DIR__);
/**
 * @var \E8\Kernel $kernel
 */
$kernel =app(\E8\Kernel::class);
$response = $kernel->handle(app(\E8\Request::class));
$response->send();
