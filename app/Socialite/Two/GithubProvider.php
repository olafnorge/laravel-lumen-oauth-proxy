<?php
namespace App\Socialite\Two;

use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Two\GithubProvider as BaseGithubProvider;

class GithubProvider extends BaseGithubProvider {


    /**
     * @return string
     */
    public function getState() {
        return parent::getState();
    }


    /**
     * @param string $token
     * @return null|string
     */
    public function getEmailByToken($token) {
        return parent::getEmailByToken($token);
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
