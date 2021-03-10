<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class DbrainApiService
{
    private string $apiUrl;
    private string $token;

    public function __construct()
    {
        $this->apiUrl = config('dbrain.api_url');
        $this->token = config('dbrain.token');
    }

    public function getClassifyTaskId($document): string
    {
        $response = Http::attach(
            'image',
            fopen($document, 'rb'),
            $document->getClientOriginalName()
        )->post("{$this->apiUrl}/classify?token={$this->token}&async=true");

        return $response->json('task_id');
    }

    public function getClassifyResponse(string $taskId): array
    {
        $classifyResponse = Http::get(
            "{$this->apiUrl}/result/{$taskId}?token={$this->token}"
        )->json();

        while((int)$classifyResponse['code'] === 202) {
            $classifyResponse = Http::get(
                "{$this->apiUrl}/result/{$taskId}?token={$this->token}"
            )->json();
        }

        return $classifyResponse;
    }

    public function getRecognizeTaskId($document)
    {
        $response = Http::attach(
            'image',
            $document,
            str_replace('documents/', '', $document)
        )->post("{$this->apiUrl}/recognize?token={$this->token}&async=true");

        return $response->json('task_id');
    }

    public function getRecognizeResponse($taskId)
    {
        $response = Http::get(
            "{$this->apiUrl}/result/{$taskId}?token={$this->token}"
        )->json();
        while((int)$response['code'] === 202) {
            $response = Http::get(
                "{$this->apiUrl}/result/{$taskId}?token={$this->token}"
            )->json();
        }

        return $response;
    }
}
