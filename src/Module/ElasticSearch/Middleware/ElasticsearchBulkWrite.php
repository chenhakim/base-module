<?php

namespace  Module\ElasticSearch\Middleware;

use App\Jobs\Elasticsearch\ElasticsearchLogWrite;
use Closure;

class ElasticsearchBulkWrite
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
        Logger('======11111', [$documents]);
        //需要判断是否有日志
        if (count($documents) > 0)
            dispatch(new ElasticsearchLogWrite($documents));
    }
}
