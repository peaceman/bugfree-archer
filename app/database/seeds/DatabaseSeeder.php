<?php

class DatabaseSeeder extends Seeder
{

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		$this->call('CountriesSeeder');
		$this->call('ResourceLocationSeeder');
		$this->call('UserSeeder');
		$this->call('ShopCategorySeeder');
		$this->call('MusicDataSeeder');
	}

}
