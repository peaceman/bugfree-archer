<?php
class ShopCategorySeeder extends Seeder
{
	public function run()
	{
		ShopCategory::create(['name' => 'shop.category.samples', 'slug' => 'samples', 'can_contain_items' => true]);
		$projectFiles = ShopCategory::create(['name' => 'shop.category.project_files', 'slug' => 'project-files', 'can_contain_items' => false]);

		$projectFiles->children()->create(['name' => 'shop.category.templates', 'slug' => 'templates', 'can_contain_items' => true]);
		$projectFiles->children()->create(['name' => 'shop.category.presets', 'slug' => 'presets', 'can_contain_items' => true]);
	}
}
