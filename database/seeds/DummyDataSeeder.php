<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Group;
use App\User;
use App\UserGroupRelation;
use App\OauthClient;

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
			if( $i < 40 ){
				$user->id = 'testuser-id-'.str_pad($i, 10, '0', STR_PAD_LEFT);
			}
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

		$company = new Group;
		$company->name = '株式会社ABC';
		$company->account = 'abc-com';
		$company->description = '';
		$company->creator_user_id = $user_id_memo[1];
		$company->created_at = $date;
		$company->updated_at = $date;
		$company->save();

		DB::table('user_group_relations')->insert([
			'user_id' => $user_id_memo[1],
			'group_id' => $company->id,
			'role' => 'owner',
		]);

		$group_jinji = new Group;
		$group_jinji->name = '人事部';
		$group_jinji->account = null;
		$group_jinji->description = '';
		$group_jinji->parent_group_id = $company->id;
		$group_jinji->root_group_id = $company->id;
		$group_jinji->creator_user_id = $user_id_memo[1];
		$group_jinji->created_at = $date;
		$group_jinji->updated_at = $date;
		$group_jinji->save();

		$group_somu = new Group;
		$group_somu->name = '総務部';
		$group_somu->account = null;
		$group_somu->description = '';
		$group_somu->parent_group_id = $company->id;
		$group_somu->root_group_id = $company->id;
		$group_somu->creator_user_id = $user_id_memo[1];
		$group_somu->created_at = $date;
		$group_somu->updated_at = $date;
		$group_somu->save();

		$group_somu_unit1 = new Group;
		$group_somu_unit1->name = 'Unit1';
		$group_somu_unit1->account = null;
		$group_somu_unit1->description = '';
		$group_somu_unit1->parent_group_id = $group_somu->id;
		$group_somu_unit1->root_group_id = $company->id;
		$group_somu_unit1->creator_user_id = $user_id_memo[1];
		$group_somu_unit1->created_at = $date;
		$group_somu_unit1->updated_at = $date;
		$group_somu_unit1->save();

	}
}
