<?php
/**
 * CleanCommand.php
 *
 * Author: Larry Li <larryli@qq.com>
 */

namespace larryli\ipv4\Command;

use larryli\ipv4\Query\Query;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CleanCommand
 * @package larryli\ipv4\Command
 */
class CleanCommand extends Command
{
    /**
     * @var null
     */
    private $progress = null;

    /**
     *
     */
    protected function configure()
    {
        $this
            ->setName('clean')
            ->setDescription('clean ip database')
            ->addArgument(
                'type',
                InputArgument::OPTIONAL,
                "file or database",
                'all');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $type = $input->getArgument('type');
        $output->writeln("<info>clean {$type}:</info>");
        switch ($type) {
            case 'all':
                $this->clean($output, 'monipdb');
                $this->clean($output, 'qqwry');
                $this->clean($output, 'full');
                $this->clean($output, 'mini');
                $this->clean($output, 'china');
                $this->clean($output, 'world');
                $this->cleanDivision($output);
                break;
            case 'file':
                $this->clean($output, 'monipdb');
                $this->clean($output, 'qqwry');
                break;
            case 'database':
                $this->clean($output, 'full');
                $this->clean($output, 'mini');
                $this->clean($output, 'china');
                $this->clean($output, 'world');
                $this->cleanDivision($output);
                break;
            default:
                throw new \Exception("Unknown type \"{$type}\".");
        }
    }

    /**
     * @param $output
     * @param $name
     * @throws \Exception
     */
    private function clean($output, $name)
    {
        $output->write("<info>clean {$name}:</info>");
        $query = Query::factory($name);
        $query->clean();
        $output->writeln('<info> completed!</info>');
    }

    /**
     * @param $output
     */
    private function cleanDivision($output)
    {
        $output->write("<info>clean divisions:</info>");
        \larryli\ipv4\Query\DatabaseQuery::cleanDivision();
        $output->writeln('<info> completed!</info>');
    }

}