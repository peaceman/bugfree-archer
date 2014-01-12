<?php
class UserSeeder extends Seeder
{
	public function run()
	{
		$id = DB::table('users')->insertGetId([
			'id' => 0,
			'email' => 'system@edm-market.com',
			'username' => 'system',
			'password' => 'what tha fuck',
			'real_name' => 'System Overlord',
			'state' => 'active',
		]);

		if ($id !== 0) {
			DB::table('users')->where('id', $id)
				->update(['id' => 0]);
		}
	}
}
