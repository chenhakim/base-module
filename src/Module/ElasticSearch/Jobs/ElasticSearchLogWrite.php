<?php
/**
 * Created by PhpStorm.
 * User: linchuan
 * Date: 5/16/20
 * Time: 11:41
 */

namespace Module\ElasticSearch\Jobs;

use Elasticsearch\Client;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\Dispatchable;

class ElasticSearchLogWrite implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $params;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $records)
    {
        $this->params['body'] = [];
        //A good start are 500 documents per bulk operation. Depending on the size of your documents you’ve to play around a little how many documents are a good number for your application.
        foreach ($records as $record) {
            $this->params['body'][] = [
                'index' => [
                    '_index' => isset($record['_index']) ? $record['_index'] : config('elasticsearch.log_name'),
                ],
            ];
            unset($record['_index'], $record['_type']);
            $this->params['body'][] = $record;
        }

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $mRsp = app('es')->getClient()->bulk($this->params);

        Logger('elk发送信息返回结果：', [$mRsp]);
    }
}