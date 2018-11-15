<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Http\Response;
use Illuminate\Validation\Concerns\ValidatesAttributes;
use Laravel\Lumen\Routing\ProvidesConvenienceMethods;

class Authenticate
{
    use ProvidesConvenienceMethods;
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $invalidResponse = [
            'code' => Response::HTTP_UNAUTHORIZED
        ];
        try {
            $this->validate($request, [
                'auth_token' => 'required'
            ]);
        } catch (\Exception $e) {
            $invalidResponse['message'] = 'You did not provide a token';
            return response($invalidResponse, Response::HTTP_FORBIDDEN);
        }
        if ($this->auth->guard($guard)->guest()) {
            $invalidResponse['message'] = 'Unauthorized';
            return response($invalidResponse, Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
