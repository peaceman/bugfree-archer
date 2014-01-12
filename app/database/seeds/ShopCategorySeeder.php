<?php
class ShopCategorySeeder extends Seeder
{
	public function run()
	{
		ShopCategory::create(['name' => 'shop.category.samples', 'slug' => 'samples']);
		$projectFiles = ShopCategory::create(['name' => 'shop.category.project_files', 'slug' => 'project-files']);

		$projectFiles->children()->create(['name' => 'shop.category.templates', 'slug' => 'templates']);
		$projectFiles->children()->create(['name' => 'shop.category.presets', 'slug' => 'presets']);
	}
}
