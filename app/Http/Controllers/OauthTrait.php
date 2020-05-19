<?php
namespace App\Http\Controllers;


use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

trait OauthTrait {

    abstract public function userByToken(Request $request): JsonResponse;
}
