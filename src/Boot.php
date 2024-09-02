<?php

namespace App;

use App\Database\Connection;
use App\Routing\Request;
use App\Session\Session;

class Boot
{
    public static function initialize(): void
    {
        Connection::init();
        Session::init();
        Request::init();
        Twig::init();
    }
}