<?php
namespace App\Http\Controllers;

use Auth;
use Cache;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Laravel\Lumen\Routing\Controller as BaseController;
use Laravel\Socialite\Contracts\Provider;
use Illuminate\Http\Request;
use Log;
use Symfony\Component\HttpFoundation\Response;

class Controller extends BaseController {

    /**
     * @var Provider
     */
    protected $provider;


    /**
     * @param Request $request
     * @return RedirectResponse|\Illuminate\Http\Response
     * @throws \Exception
     * @throws \Throwable
     */
    public function callback(Request $request): Response {
        $this->log($request, __METHOD__);
        $redirect = Cache::pull($request->query('state'));

        if (!$redirect) {
            return response(view('404', [
                'state' => $request->query('state'),
            ])->render(), 404);
        }

        return redirect($this->buildRedirect($redirect, $request->toArray()));
    }


    /**
     * @param Request $request
     * @return RedirectResponse|\Illuminate\Http\Response
     * @throws \Throwable
     */
    public function authUrl(Request $request): Response {
        $this->log($request, __METHOD__);

        if (Auth::user()->redirect_uri !== $request->query('redirect_uri')) {
            return response(view('400', [
                'redirectUri' => $request->query('redirect_uri'),
                'clientId' => Auth::user()->client_id,
                'scope' => $request->query('scope'),
                'responseType' => $request->query('response_type'),
                'state' => $request->query('state'),
            ])->render(), 400);
        }

        $state = $request->query('state') ?: $this->provider->getState();
        Cache::put($state, Auth::user()->redirect_uri, 5);

        return $this->provider->with($request->except(['client_id', 'client_secret', 'redirect_uri']))->redirect($state);
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function tokenUrl(Request $request): JsonResponse {
        $this->log($request, __METHOD__);

        try {
            return response()->json($this->provider->getAccessTokenResponse($request->input('code')));
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
    public function userByToken(Request $request): JsonResponse {
        $this->log($request, __METHOD__);

        try {
            return response()->json($this->provider->getUserByToken($request->bearerToken()));
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
     * @param string $method
     */
    protected function log(Request $request, string $method): void {
        if (config('app.debug')) {
            Log::info($method, [
                'query' => $request->query(),
                'input' => $request->input(),
                'method' => $request->method(),
                'headers' => $request->header(),
            ]);
        }
    }


    /**
     * @param string $url
     * @param array $parameters
     * @return string
     */
    private function buildRedirect(string $url, array $parameters): string {
        $query = parse_url($url, PHP_URL_QUERY);
        $fragment = parse_url($url, PHP_URL_FRAGMENT);

        if ($query) {
            $firstSeperator = '&';
        } else {
            $firstSeperator = '?';
        }

        if ($fragment) {
            $url = str_replace('#' . $fragment, '', $url);
        }

        $i = 0;

        foreach ($parameters as $parameter => $value) {
            if ($i == 0) {
                $url .= $firstSeperator . $parameter . '=' . $value;
            } else {
                $url .= '&' . $parameter . '=' . $value;
            }

            $i++;
        }

        if ($fragment) {
            $url .= '#' . $fragment;
        }

        return $url;
    }
}
