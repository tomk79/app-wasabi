<?php
namespace App\Wasabi\Pickles2;

class pickles2{

	public static function web($requestm, $project_id, $params){
		ob_start();
		var_dump($project_id, $params);
		$fin = ob_get_clean();

		return view(
			'pickles2::index',
			['main'=>$fin]
		);
	}

}
