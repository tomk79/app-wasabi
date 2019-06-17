<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Group;
use App\Project;
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

		$company1 = new Group;
		$company1->name = '株式会社ABC';
		$company1->account = 'abc-com';
		$company1->description = '';
		$company1->creator_user_id = $user_id_memo[1];
		$company1->created_at = $date;
		$company1->updated_at = $date;
		$company1->save();

		$company1_jinji = new Group;
		$company1_jinji->name = '人事部';
		$company1_jinji->account = null;
		$company1_jinji->description = '';
		$company1_jinji->parent_group_id = $company1->id;
		$company1_jinji->root_group_id = $company1->id;
		$company1_jinji->creator_user_id = $user_id_memo[1];
		$company1_jinji->created_at = $date;
		$company1_jinji->updated_at = $date;
		$company1_jinji->save();

		$company1_somu = new Group;
		$company1_somu->name = '総務部';
		$company1_somu->account = null;
		$company1_somu->description = '';
		$company1_somu->parent_group_id = $company1->id;
		$company1_somu->root_group_id = $company1->id;
		$company1_somu->creator_user_id = $user_id_memo[1];
		$company1_somu->created_at = $date;
		$company1_somu->updated_at = $date;
		$company1_somu->save();

		$company1_somu_unit1 = new Group;
		$company1_somu_unit1->name = 'Unit1';
		$company1_somu_unit1->account = null;
		$company1_somu_unit1->description = '';
		$company1_somu_unit1->parent_group_id = $company1_somu->id;
		$company1_somu_unit1->root_group_id = $company1->id;
		$company1_somu_unit1->creator_user_id = $user_id_memo[1];
		$company1_somu_unit1->created_at = $date;
		$company1_somu_unit1->updated_at = $date;
		$company1_somu_unit1->save();

		DB::table('user_group_relations')->insert([
			'user_id' => $user_id_memo[1],
			'group_id' => $company1->id,
			'role' => 'owner',
		]);

		$company2 = new Group;
		$company2->name = '株式会社DEF';
		$company2->account = null;
		$company2->description = null;
		$company2->creator_user_id = $user_id_memo[1];
		$company2->created_at = $date;
		$company2->updated_at = $date;
		$company2->save();

		$company2_seisaku = new Group;
		$company2_seisaku->name = '制作部';
		$company2_seisaku->account = null;
		$company2_seisaku->description = '';
		$company2_seisaku->parent_group_id = $company2->id;
		$company2_seisaku->root_group_id = $company2->id;
		$company2_seisaku->creator_user_id = $user_id_memo[1];
		$company2_seisaku->created_at = $date;
		$company2_seisaku->updated_at = $date;
		$company2_seisaku->save();

		$company2_seisaku_dev = new Group;
		$company2_seisaku_dev->name = '開発UNIT';
		$company2_seisaku_dev->account = null;
		$company2_seisaku_dev->description = '';
		$company2_seisaku_dev->parent_group_id = $company2_seisaku->id;
		$company2_seisaku_dev->root_group_id = $company2->id;
		$company2_seisaku_dev->creator_user_id = $user_id_memo[1];
		$company2_seisaku_dev->created_at = $date;
		$company2_seisaku_dev->updated_at = $date;
		$company2_seisaku_dev->save();

		$company2_seisaku_dev_g1 = new Group;
		$company2_seisaku_dev_g1->name = 'グループ1';
		$company2_seisaku_dev_g1->account = null;
		$company2_seisaku_dev_g1->description = '';
		$company2_seisaku_dev_g1->parent_group_id = $company2_seisaku_dev->id;
		$company2_seisaku_dev_g1->root_group_id = $company2->id;
		$company2_seisaku_dev_g1->creator_user_id = $user_id_memo[1];
		$company2_seisaku_dev_g1->created_at = $date;
		$company2_seisaku_dev_g1->updated_at = $date;
		$company2_seisaku_dev_g1->save();

		DB::table('user_group_relations')->insert([
			'user_id' => $user_id_memo[1],
			'group_id' => $company2_seisaku->id,
			'role' => 'obserber',
		]);
		DB::table('user_group_relations')->insert([
			'user_id' => $user_id_memo[1],
			'group_id' => $company2_seisaku_dev->id,
			'role' => 'manager',
		]);
		DB::table('user_group_relations')->insert([
			'user_id' => $user_id_memo[1],
			'group_id' => $company2_seisaku_dev_g1->id,
			'role' => 'member',
		]);


		$project_px2 = new Project;
		$project_px2->name = 'Pickles2';
		$project_px2->group_id = $company1_somu_unit1->id;
		$project_px2->creator_user_id = $user_id_memo[1];
		$project_px2->private_flg = true;
		$project_px2->created_at = $date;
		$project_px2->updated_at = $date;
		$project_px2->save();

		DB::table('user_project_relations')->insert([
			'user_id' => $user_id_memo[1],
			'project_id' => $project_px2->id,
			'role' => 'owner',
		]);

	}
}
