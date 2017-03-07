<?php
require __DIR__ . '/vendor/autoload.php';

use mpyw\Cowitter\Client;

$json = json_decode(file_get_contents(__DIR__ . '/config.json'));

$client = new Client([
    $json->consumer_key,
    $json->consumer_secret,
    $json->access_token,
    $json->access_token_secret
]);

$dir = scandir($json->dir);

$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($json->dir));

$mtime_max = 0;
foreach ($iterator as $fileinfo) {
    if ($fileinfo->isFile() && $mtime_max < $fileinfo->getMtime()) {
        $filepath = $fileinfo->getPathname();
    }
}

$client->post('statuses/update', ['status' => $argv[1], 'media_ids' => $client->post('media/upload', ['media' => new \CURLFile($filepath)])->media_id_string]);
