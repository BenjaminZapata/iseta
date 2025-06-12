<?php

namespace App\Logging;

use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use Monolog\LogRecord;

class LokiHandler extends AbstractProcessingHandler
{
    protected Client $client;

    protected string $url;

    protected string $username;

    protected string $password;

    public function __construct($level = Logger::DEBUG, bool $bubble = true)
    {
        parent::__construct($level, $bubble);

        $this->client = new Client;
        $this->url = rtrim(env('LOKI_URL', ''), '/');
        $this->username = env('LOKI_USER', '');
        $this->password = env('LOKI_PASSWORD', '');
    }

    protected function write(LogRecord $record): void
    {
        try {
            // Get current time in nanoseconds using Carbon
            $timestamp = Carbon::now()->timestamp * 1000000000;

            // Construct Loki JSON entry with message and timestamp
            $logEntry = [
                'streams' => [
                    [
                        'stream' => [
                            'job' => 'epi-counter',
                            'level' => strtolower($record->level->getName()),
                        ],
                        'values' => [
                            [(string) $timestamp, $record->message],
                        ],
                    ],
                ],
            ];

            $response = $this->client->post($this->url.'/loki/api/v1/push', [
                'auth' => [$this->username, $this->password],
                'json' => $logEntry,
                'headers' => ['Content-Type' => 'application/json'],
            ]);
        } catch (RequestException $e) {
            // Handle errors if push fails
            error_log('🚨 Loki push failed: '.$e->getMessage());
            if ($e->hasResponse()) {
                error_log('🔍 Response: '.$e->getResponse()->getBody()->getContents());
            }
        }
    }
}

