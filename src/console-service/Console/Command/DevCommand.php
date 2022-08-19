<?php

namespace Bachtiar\Helper\ConsoleService\Console\Command;

use Bachtiar\Helper\LaminasLogger\Service\LogService;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\State;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class DevCommand extends Command
{
    //

    protected const INPUT_CLASS_PATH = 'class';
    protected const INPUT_CLASS_PATH_SHORT = 'C';
    protected const INPUT_METHOD_NAME = 'method';
    protected const INPUT_METHOD_NAME_SHORT = 'M';

    /**
     * {@inheritDoc}
     */
    public function __construct(
        State $state
    ) {
        parent::__construct();
        $this->state = $state;
    }

    // ? Protected Methods
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this->setName('bachtiar:console:dev')
            ->setDescription('Run console command for development mode')
            ->setDefinition([
                new InputOption(
                    self::INPUT_CLASS_PATH,
                    self::INPUT_CLASS_PATH_SHORT,
                    InputOption::VALUE_REQUIRED,
                    'Set class path'
                ),
                new InputOption(
                    self::INPUT_METHOD_NAME,
                    self::INPUT_METHOD_NAME_SHORT,
                    InputOption::VALUE_REQUIRED,
                    'Set method name'
                )
            ]);

        parent::configure();
    }

    // ? Public Methods
    /**
     * {@inheritDoc}
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->state->setAreaCode('global');

        try {
            $_objectManager = ObjectManager::getInstance();

            $_class = $_objectManager->create($input->getOption(self::INPUT_CLASS_PATH));

            $_method = $_class->{$input->getOption(self::INPUT_METHOD_NAME)}();

            $output->writeln('Successfully execute console command');

            LogService::title($input->getOption(self::INPUT_CLASS_PATH) . '::' . $input->getOption(self::INPUT_METHOD_NAME))->log($_method);

            return 1;
        } catch (\Throwable $th) {
            $output->writeln($th->getMessage());

            return 0;
        }
    }

    // ? Private Methods

    // ? Setter Modules
}
