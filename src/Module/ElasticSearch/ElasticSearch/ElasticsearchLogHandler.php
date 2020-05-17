<?php
/**
 * Created by PhpStorm.
 * User: linchuan
 * Date: 5/16/20
 * Time: 11:37
 */

namespace  Module\ElasticSearch\ElasticSearch;

use Monolog\Handler\AbstractProcessingHandler;

class ElasticsearchLogHandler extends AbstractProcessingHandler
{
    protected function write(array $record)
    {
        if ($record['level'] >= 200)
            app('es')->addDocument($record);
    }
}