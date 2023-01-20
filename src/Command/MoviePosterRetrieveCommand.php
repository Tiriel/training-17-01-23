<?php

namespace App\Command;

use App\Omdb\OmdbGateway;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:movie:poster-retrieve',
    description: 'Mise a jour des fiches qui n\'ont pas de poster',
)]
class MoviePosterRetrieveCommand extends Command
{
    private OmdbGateway $omdbGateway;
    private MovieRepository $movieRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(
        OmdbGateway $omdbGateway,
        MovieRepository $movieRepository,
        EntityManagerInterface $entityManager
    )
    {
        $this->omdbGateway = $omdbGateway;
        $this->movieRepository = $movieRepository;
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $noPosterMovies = $this->movieRepository->findEmptyPosterMovies();

        if($io->isVerbose()) {
            $io->note(sprintf('Il y a %d films sans poster', count($noPosterMovies)));
        }

        $numberOfUpdates = 0;
        foreach($noPosterMovies as $persistedMovie) {
            try {
                $apiMovie = $this->omdbGateway->fetchMovie($persistedMovie->getTitle());
                if(!empty($apiMovie->getPoster())) {
                    $persistedMovie->setPoster($apiMovie->getPoster());
                    $this->entityManager->persist($persistedMovie);
                    $numberOfUpdates++;
                }
            } catch(\Throwable $e) {
                if($io->isVeryVerbose()) {
                    $io->error($e->getMessage());
                }
            }
        }

        $this->entityManager->flush();

        $io->success(sprintf('%d fiches ont été mises à jour.', $numberOfUpdates));

        return Command::SUCCESS;
    }
}
