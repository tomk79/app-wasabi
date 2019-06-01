<?php

use Illuminate\Database\Seeder;
use App\Org;
use App\User;
use App\UserOrgRelation;

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

		$org = new Org;
		$org->name = '株式会社ABC';
		$org->account = 'abc-com';
		$org->description = '';
		$org->creator_user_id = $user_id_memo[0];
		$org->created_at = $date;
		$org->updated_at = $date;
		$org->save();

		DB::table('user_org_relations')->insert([
			'user_id' => $user_id_memo[0],
			'org_id' => $org->id,
			'role' => 'owner',
		]);

	}
}
