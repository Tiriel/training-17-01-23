<?php

namespace App\Command;

use App\Omdb\OmdbGateway;
use App\Repository\MovieRepository;
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

    public function __construct(OmdbGateway $omdbGateway, MovieRepository $movieRepository)
    {
        $this->omdbGateway = $omdbGateway;
        $this->movieRepository = $movieRepository;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $noPosterMovies = $this->movieRepository->findEmptyPosterMovies();

        foreach($noPosterMovies as $movie) {
            
        }

        $io->success(sprintf('%d fiches ont été mises à jour.', $numberOfUpdates));

        return Command::SUCCESS;
    }
}
