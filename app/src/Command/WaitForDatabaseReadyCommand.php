<?php
namespace App\Command;

use App\Service\DatabaseChecker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class WaitForDatabaseReadyCommand extends Command
{
    const MAX_TRIES = 10;

    protected static $defaultName = 'app:wait-for-database-ready';

    private $databaseChecker;

    public function __construct(DatabaseChecker $databaseChecker)
    {
        $this->databaseChecker = $databaseChecker;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Wait for the database ready')
            ->setHelp('Since the app depends on the database not just being running, we must wait for a successful ' .
                'connection');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Waiting for database ready');
        for ($i = 1; $i <= self::MAX_TRIES; $i++) {
            if ($this->databaseChecker->isDatabaseReady()) {
                $output->writeln('Database is ready');
                return;
            }
            $output->write('.');
            sleep(1);
        }
        $output->writeln('WARNING: Database was NOT ready after the maximum tries. Subsequent database queries may '
            . 'fail. Try checking the database logs to resolve.');
    }
}