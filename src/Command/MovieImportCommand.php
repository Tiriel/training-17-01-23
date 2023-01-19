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
    name: 'app:movie:import',
    description: 'Importer la fiche du film.',
)]
class MovieImportCommand extends Command
{
    private OmdbGateway $omdbGateway;
    private MovieRepository $movieRepository;

    public function __construct(OmdbGateway $omdbGateway, MovieRepository $movieRepository)
    {
        $this->omdbGateway = $omdbGateway;
        $this->movieRepository = $movieRepository;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('search', InputArgument::REQUIRED, 'Titre du film')
//            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $search = $input->getArgument('search');

        try {
            $movie = $this->omdbGateway->fetchMovie($search);

            // Persist the movie in database
            $this->movieRepository->add($movie, true);
        } catch(\Throwable $e) {
            $io->error($e->getMessage());
            return Command::FAILURE;
        }

        $io->success(sprintf('Le film %s a été importé.', $movie->getTitle()));
        return Command::SUCCESS;
    }
}
