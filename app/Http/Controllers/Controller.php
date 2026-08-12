<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 * @class Controller
 *
 * @package App\Http\Controllers
 */
abstract class Controller
{
    use AuthorizesRequests;
}
