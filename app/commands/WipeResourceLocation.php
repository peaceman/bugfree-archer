<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class WipeResourceLocation extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'app:wipe-resource-location';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Deletes all resource files from the resource location with the given id';

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
			$this->error("Can't find a resource location with id $resourceLocationId");
			return 1;
		}

		/** @var \EDM\Resource\Storage\StorageDirector $storageDirector */
		$storageDirector = App::make('storage-director');
		$storageDirector->queueWipingOfResourceLocation($resourceLocation);
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
