<?php

use Illuminate\Database\Seeder;
use App\Group;
use App\User;
use App\UserGroupRelation;

class DummyDataSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{

		// 固定ユーザーを作成
		$user_id_memo = array();
		for( $i = 0; $i < 100; $i++ ){
			$date = date('Y-m-d H:i:s');

			$user = new User;
			$user->name = 'Test'.$i;
			$user->account = 'test'.$i;
			$user->email = 'test'.$i.'@example.com';
			$user->password = bcrypt('password');
			$user->lang = 'ja';
			$user->email_verified_at = $date;
			$user->created_at = $date;
			$user->updated_at = $date;
			$user->save();

			$user_id_memo[$i] = $user->id;
		}

		$group = new Group;
		$group->name = '株式会社ABC';
		$group->account = 'abc-com';
		$group->description = '';
		$group->creator_user_id = $user_id_memo[0];
		$group->created_at = $date;
		$group->updated_at = $date;
		$group->save();

		DB::table('user_group_relations')->insert([
			'user_id' => $user_id_memo[0],
			'group_id' => $group->id,
			'role' => 'owner',
		]);

	}
}
