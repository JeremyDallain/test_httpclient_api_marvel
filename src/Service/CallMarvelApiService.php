<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CallMarvelApiService
{
    private HttpClientInterface $client;
    private ParameterBagInterface $parameterBag;

    public function __construct(HttpClientInterface $client, ParameterBagInterface $parameterBag)
    {
        $this->client = $client;
        $this->parameterBag = $parameterBag;
    }

    public function getCharacters(int $limit, int $offset, string $search = null) : array
    {
        $query = "characters?limit=$limit&offset=$offset";

        if ($search) {
            $query .= "&nameStartsWith=$search";
        }

        $apiDatas = $this->getApi($query)->toArray();

        return $apiDatas;
    }

    public function getCharacter(int $id) : array
    {
        $query = "characters/$id";
        $apiDatas = $this->getApi($query)->toArray();

        return $apiDatas;
    }

    private function getApi(string $query)
    {
        $response = $this->client->request(
            'GET',
            "https://gateway.marvel.com:443/v1/public/$query",
            [
                'query' => [
                    'ts' => $this->parameterBag->get('marvel_timestamp'),
                    'apikey' => $this->parameterBag->get('marvel_public_key'),
                    'hash' => $this->parameterBag->get('marvel_hash')
                ]
            ]
        );

        return $response;
    }
}
