<?php
return [
    'hosts'=>[
        env('ELASTIC_HOST', 'http://localhost:9200')
    ],
    'name'=>env('ELASTIC_AUTH_NAME', 'elastic'),
    'password'=>env('ELASTIC_AUTH_NAME', 'changeme'),
    'log_name'=>env('ELASTIC_LOG_NAME', 'laravel-log'),
    'queue_name'=>env('ELASTIC_QUEUE_NAME', 'elk-log'),
];