<?php

namespace App\Http\Controllers;

use App\Socialite\Two\LinkedInProvider;
use Illuminate\Http\Request;

class LinkedinController extends Controller {


    /**
     * LinkedinController constructor.
     */
    public function __construct() {
        $this->provider = new LinkedInProvider(
            new Request(),
            config('services.linkedin.client_id'),
            config('services.linkedin.client_secret'),
            config('services.linkedin.redirect')
        );
    }
}
