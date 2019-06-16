<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use Artisan;

class oauthApiTest extends TestCase
{

	/**
	 * Setup
	 */
	public function setUp(): void{
		parent::setUp();

		if( !env('APP_DEBUG') || !is_file('./.testing') ){
			// Fool proof
			return;
		}

		copy(__DIR__.'/../testdata/oauth/oauth-private.key', __DIR__.'/../../storage/oauth-private.key');
		copy(__DIR__.'/../testdata/oauth/oauth-public.key', __DIR__.'/../../storage/oauth-public.key');

		//artisan
		Artisan::call('migrate:refresh');
		Artisan::call('db:seed');
		Artisan::call('db:seed', array('--class'=>'DummyDataSeeder'));
	}

	/**
	 * A basic test.
	 *
	 * @return void
	 */
	public function testBasicTest()
	{
		// Fool proof
		$this->assertTrue( env('APP_DEBUG') );
		$this->assertTrue( is_file('./.testing') );

		$access_token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjIwZTQ1ZDU4MmViNzliNTY1YzMwMTdhZmExNmJhMzY4NTUzZTgwNzU0YWUxYzUzMzcyM2Y5MjcxOWVlZTY3YjVhNTM5YzI1YWFjYjIxOGE2In0.eyJhdWQiOiI4MTVjZWNhMC0xM2E4LTQ3YWEtYTM1Yy01YmI1ZjdhZmFmYjUiLCJqdGkiOiIyMGU0NWQ1ODJlYjc5YjU2NWMzMDE3YWZhMTZiYTM2ODU1M2U4MDc1NGFlMWM1MzM3MjNmOTI3MTllZWU2N2I1YTUzOWMyNWFhY2IyMThhNiIsImlhdCI6MTU2MDcwMzIxMSwibmJmIjoxNTYwNzAzMjExLCJleHAiOjE1OTIzMjU2MTEsInN1YiI6InRlc3R1c2VyLWlkLTAwMDAwMDAwMDEiLCJzY29wZXMiOltdfQ.ScjPl2jobId5qJyONufE0zbOr0u_qCkJdwha6BZ2bE7MZuXx-ZUs8WhQsfiBTDmvIbWxJw4tfvUz-XIpJDh8VIB99HkGISFAyHiidLG8TQqtr2NtDhP5Ra1m7hUmIBL7gsVrfcG6mWTKwZ0jaMANwwLUqVzfihOkoZDZfuLoYMDC53fx82rpcz-jJtofvQhiA313ML4GvVLGuh6L672mYqByU_NCKqNVqX131CJp-Gt4jipbYAxJSdoDRdyL8MXnL10TfMVhRiAQGplNXJOcCrVAYrkmH5fHvshMFc6Z4bXy0hPJ30y55GOoS1py66HW1GruNEestzaT4vqAPJjoo2bGlODARU5vTMzLf_hbBYucEARq-Q38-A4ni0rg1rh2tQfDi24iYX3NGgk3YUzGjmOAGvPXiYbwhho0SHVjD9mCRs61yMK6g5Vi6SiQVIlUVMo3YL-k9HK_QxDx8fgaJmkrv-q2-px_wJvvF5cJUmIIy_cwN8nF1zMScg-0fegkPUG0Mw82CTBk1kHP_v9kXZ7Y_ebdVJCB4dIUYmjA_d8zSCRIh4YWNOhejQteUu6LUT-iGagNuQovkRAhMKWWQLYgPqLP8oHMjVuzn_5HGukTRJX7r7QR1zlNcKpHWAizF6v8rGZ8OxJXnajJs6skaYJ3_fqEOYZ_UFDfhtCAj_o';

		$user = User::where(array('id'=>'testuser-id-0000000001'))->first();
		$response = $this->actingAs($user)->get('/settings/profile');
		$response->assertStatus(200);
		$response->assertSeeText('test1@example.com');

		$response = $this->withHeaders([
			'Authorization' => 'Bearer '.$access_token,
		])->json('GET', '/api/user');
		$response->assertStatus(200)
			->assertJson([
				'id' => 'testuser-id-0000000001',
				'name' => 'Test1',
				'email' => 'test1@example.com',
			])
		;
	}
}
