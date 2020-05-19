<?php

namespace App\Http\Controllers;

use App\Socialite\Two\GithubProvider;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GithubController extends Controller {


    /**
     * GithubController constructor.
     */
    public function __construct() {
        $this->provider = new GithubProvider(
            new Request(),
            config('services.github.client_id'),
            config('services.github.client_secret'),
            config('services.github.redirect')
        );
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function userByToken(Request $request): JsonResponse {
        $this->log($request, __METHOD__);

        try {
            return response()->json($this->provider->getUserByToken($request->query('access_token')));
        } catch (RequestException $exception) {
            $data = [];
            $status = 500;
            $headers = [];

            if ($exception->hasResponse()) {
                $data = json_decode($exception->getResponse()->getBody(), true);
                $status = $exception->getResponse()->getStatusCode();
                $headers = $exception->getResponse()->getHeaders();
            }

            return response()->json($data, $status, $headers);
        }
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function emailByToken(Request $request): JsonResponse {
        $this->log($request, __METHOD__);

        try {
            return response()->json($this->provider->getEmailByToken($request->query('access_token')));
        } catch (RequestException $exception) {
            $data = [];
            $status = 500;
            $headers = [];

            if ($exception->hasResponse()) {
                $data = json_decode($exception->getResponse()->getBody(), true);
                $status = $exception->getResponse()->getStatusCode();
                $headers = $exception->getResponse()->getHeaders();
            }

            return response()->json($data, $status, $headers);
        }
    }
}
