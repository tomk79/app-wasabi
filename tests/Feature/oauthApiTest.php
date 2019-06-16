<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        // Fool proof
        $this->assertTrue( env('APP_DEBUG') );
        $this->assertTrue( is_file('./.testing') );

        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
