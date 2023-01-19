<?php
declare(strict_types=1);

namespace App\Omdb;

use App\Entity\Movie;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class OmdbGateway
{
    private HttpClientInterface $httpClient;

    public function __construct(
        HttpClientInterface $httpClient,
    )
    {
        $this->httpClient = $httpClient;
    }

    public function fetchMovie(?string $title): Movie
    {
        $apiResponse = $this->httpClient->request(
            'GET',
            'http://www.omdbapi.com/?apikey=e0ded5e2&t=' . $title
        )->toArray();

        $movie = new Movie();

        $released = \DateTimeImmutable::createFromFormat(
            'd M Y', $apiResponse['Released']
        );
        $movie->setTitle($apiResponse['Title']);
        $movie->setReleasedAt($released ?: new \DateTimeImmutable());
        $movie->setPlot($apiResponse['Plot']);
        $movie->setCountry($apiResponse['Country']);
        $movie->setPoster($apiResponse['Poster']);

        return $movie;
    }
}