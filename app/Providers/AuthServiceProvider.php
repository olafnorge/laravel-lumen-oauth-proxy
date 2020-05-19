<?php

namespace App\Providers;

use App\Models\Client;
use Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider {


    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {
        //
    }


    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot() {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

        $this->app['auth']->viaRequest('api', function (Request $request) {
            return Client::where('client_id', $request->input('client_id'))
                ->where('activated', true)
                ->first();
        });

        $this->app['auth']->viaRequest('client_secret', function (Request $request) {
            return Hash::check($request->input('client_secret'), Auth::user()->client_secret)
                ? Auth::user()
                : null;
        });
    }
}
