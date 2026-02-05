<?php
use Illuminate\Support\Facades\Route;

Auth::routes();

require __DIR__ . '/system/lang.php';
require __DIR__ . '/system/home.php';
require __DIR__ . '/system/general.php';
require __DIR__ . '/system/users.php';
require __DIR__ . '/system/roles.php';
require __DIR__ . '/system/autocompletes.php';
//sitio web
require __DIR__ . '/website/welcome.php';
require __DIR__ . '/website/properties.php';
require __DIR__ . '/google/google.php';
require __DIR__ . '/website/general.php';
require __DIR__ . '/owner/owner.php';
