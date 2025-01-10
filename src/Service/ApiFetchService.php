<?php
Namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;


class ApiFetchService
{
    public function __construct(
        private HttpClientInterface $client,
    ) {
    }

    public function getApiData(?String $url , ?String $token = null, ): array
    {
        $response = $this->client->request(
            'GET',
            $url,
            [
                'auth_bearer' => $token,

            ]
        );

        $statusCode = $response->getStatusCode();
        // $statusCode = 200
        $contentType = $response->getHeaders()['content-type'][0];
        // $contentType = 'application/json'
        $content = $response->getContent();
        // $content = '{"id":521583, "name":"symfony-docs", ...}'
        $content = $response->toArray();
        // $content = ['id' => 521583, 'name' => 'symfony-docs', ...]

        return $content['data'];
    }


    
}