<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class FillResourceLocation extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'app:fill-resource-location';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Transfers all resource files to the resource location with the given id';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$resourceLocationId = $this->argument('id');
		$resourceLocation = ResourceLocation::find($resourceLocationId);
		if (!$resourceLocation) {
			$this->error(sprintf("Can't find a resource location with id %d", $resourceLocationId));
			return 1;
		}

		/** @var \EDM\Resource\Storage\StorageDirector $storageDirector */
		$storageDirector = App::make('storage-director');
		$storageDirector->queueFillingOfResourceLocation($resourceLocation);
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('id', InputArgument::REQUIRED, 'ResourceLocation Id'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
//			array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
	}

}
