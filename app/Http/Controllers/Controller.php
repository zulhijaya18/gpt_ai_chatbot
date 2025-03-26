<?php

namespace App\Http\Controllers;

use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

abstract class Controller
{
    protected function jwtAuth()
    {
        try {
            JWTAuth::parseToken()->authenticate();
            return ['status' => 'success'];
        } catch (TokenExpiredException $e) {
            return ['status' => 'Token expired'];
        } catch (TokenInvalidException $e) {
            return ['status' => 'Token invalid'];
        } catch (JWTException $e) {
            return ['status' => 'Token absent'];
        }
    }
}
