<?php
 
namespace App\Http\Middleware;
 
use Closure;
use Log;
 
class RequestResponseLogger
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
        
        return $next($request);
    }
 
    public function terminate($request, $response)
    {
        //Log All Requests and Responses 
        Log::info('requests', [
            'request' => $request->all(),
            'User' => "API User",
            'IP Address' => $request->getClientIp(),
            'Method' => $request->getMethod(),
            'URL' => $request->fullUrl(),
            'response Code' => $response->getStatusCode(),
        ]);    
    
    }
}
