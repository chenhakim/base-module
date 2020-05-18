<?php

namespace  Module\ElasticSearch\Middleware;


use Closure;
use Module\ElasticSearch\Jobs\ElasticSearchLogWrite;

class ElasticSearchBulkWrite
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

    public function terminate($request, $response){
        $documents = app('es')->getDocuments();
        // 需要判断是否有日志
        if (count($documents) > 0)
            dispatch(new ElasticSearchLogWrite($documents))->onQueue(config('module-elastic-search.queue_name'));
    }
}
