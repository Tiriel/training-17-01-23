<?php
declare(strict_types=1);

namespace App\Omdb;

use App\Entity\Movie;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class OmdbGateway
{
    private HttpClientInterface $httpClient;
    private AuthorizationCheckerInterface $authorizationChecker;

    public function __construct(
        HttpClientInterface $httpClient,
        AuthorizationCheckerInterface $authorizationChecker,
    )
    {
        $this->httpClient = $httpClient;
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * @IsGranted()
     */
    public function fetchMovie(?string $title): Movie
    {
        if (!$this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            throw new \Exception('You are not allowed to do this');
        }

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