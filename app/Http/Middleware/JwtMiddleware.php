<?php

namespace App\Http\Middleware;

use Closure;
use JWTFactory;
use JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JwtMiddleware extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // return response()->json(['success' => false,'info' => ""]);
        try {
                            
            $user = auth()->userOrFail();
                            
        }
        catch(\Exception $e){
           $error = 'Invalid token';
           return response()->json(['success' => false,'info' => $error]);
          
         }
         /*catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

                return response()->json(['token_expired'], $e->getStatusCode());

        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

                return response()->json(['token_invalid'], $e->getStatusCode());

        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {

                return response()->json(['token_absent'], $e->getStatusCode());

        }*/

        return $next($request);
    }
}
