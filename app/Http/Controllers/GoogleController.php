<?php

namespace App\Http\Controllers;

use App\Socialite\Two\GoogleProvider;
use Illuminate\Http\Request;

class GoogleController extends Controller {


    /**
     * GoogleController constructor.
     */
    public function __construct() {
        $this->provider = new GoogleProvider(
            new Request(),
            config('services.google.client_id'),
            config('services.google.client_secret'),
            config('services.google.redirect')
        );
    }
}
