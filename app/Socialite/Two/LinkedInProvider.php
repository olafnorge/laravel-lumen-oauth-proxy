<?php
namespace App\Socialite\Two;

use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Two\LinkedInProvider as BaseLinkedInProvider;

class LinkedInProvider extends BaseLinkedInProvider {


    /**
     * @return string
     */
    public function getState() {
        return parent::getState();
    }


    /**
     * @param string $token
     * @return array|mixed
     */
    public function getUserByToken($token) {
        return parent::getUserByToken($token);
    }


    /**
     * @param string $state
     * @return RedirectResponse
     */
    public function redirect(string $state = '') {
        return new RedirectResponse($this->getAuthUrl($state));
    }
}
