<?php
/**
 * Created by PhpStorm.
 * User: linchuan
 * Date: 5/16/20
 * Time: 11:37
 */

namespace  Module\ElasticSearch\ElasticSearch;

use Elasticsearch\ClientBuilder;

class ElasticsearchClient
{
    protected $client;

    protected $documents = [];

    public function __construct()
    {
        $bulider = ClientBuilder::create()
            ->setHosts(config('elasticsearch.hosts'))
            ->setBasicAuthentication(config('elasticsearch.name'), config('elasticsearch.password'));
        if(app()->environment() == 'local')  {
            // 配置日志，Elasticsearch 的请求和返回数据将打印到日志文件中，方便我们调试
            $bulider->setLogger(app('log'));
        }
        return $this->client = $bulider->build();
    }

    public function getClient()
    {
        return $this->client;
    }

    /**
     * @function Name addDocument
     * @description 添加日志
     * @param array $document
     */
    public function addDocument(array $document)
    {
        $this->documents[] = $document;
    }

    /**
     * @function Name getDocuments
     * @description 获取所有已添加日志
     * @return mixed
     */
    public function getDocuments()
    {
        return $this->documents;
    }
}