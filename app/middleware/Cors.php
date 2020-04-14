<?php

namespace app\middleware;

class Cors
{
    public function handle($request, \Closure $next)
    {
    	
    	$response = $next($request);
        $origin = $request->header('Origin');
        //OPTIONS请求返回204请求
        if ($request->method(true) === 'OPTIONS') {
            $response->code(204);
        }
        $response->header([
            'Access-Control-Allow-Origin'      => $origin,
            'Access-Control-Allow-Methods'     => 'POST',
            'Access-Control-Allow-Credentials' => 'true',
            'Access-Control-Allow-Headers'     => 'token,tokenid,content-type',
        ]);

        return $response;
    }
}