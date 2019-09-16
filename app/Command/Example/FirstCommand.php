<?php

namespace W7\App\Command\Example;

use Symfony\Component\Console\Input\InputOption;
use W7\Console\Command\CommandAbstract;

class FirstCommand extends CommandAbstract {
	protected function configure() {
		$this->addOption('--test', '-o', InputOption::VALUE_REQUIRED, 'the option desc');
	}

	protected function handle($options) {
		$this->output->writeln('the option test value is ' . $options['test'] ?? '');
		$this->output->writeln('process command');
	}
}