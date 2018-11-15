<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class AdsTest extends TestCase
{
    use DatabaseTransactions;
    var $routeUrl = '/ads';
    /** @var \App\Ad  */
    var $testAd = null;
    var $authUser = null;
    var $authToken = null;
    public function setUp()
    {
        parent::setUp();
        $this->authUser = factory(\App\User::class)->make();
        $this->authToken = ['auth_token' => $this->authUser->auth_token];
        $this->authUser->save();
        factory(\App\User::class, 10)->create();
        /** @var \Illuminate\Database\Eloquent\Collection $ads */
        $ads = factory(\App\Ad::class, 100)->make()->each(function($ad) {
            $ad->user()->associate(\App\User::all()->random(1)->first());
            $ad->save();
        });
        $this->testAd = $ads->random(1)->first();
    }
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGetAllAds()
    {
        $this->get($this->routeUrl);
        $this->assertResponseOk();
        $this->seeJson($this->testAd->toArray());
    }

    public function testFailAuthentication()
    {
        $newAd = (factory(\App\Ad::class)->make());

        $this->post($this->routeUrl, $newAd->toArray());
        $this->assertResponseStatus(\Illuminate\Http\Response::HTTP_FORBIDDEN);
    }

    public function testCreateAdAndSuccess()
    {
        $newAd = (factory(\App\Ad::class)->make());
        $response = $this->post($this->routeUrl, array_merge($newAd->toArray(), $this->authToken));
        $this->assertResponseOk();
        $this->seeJson($newAd->toArray());

        return $response;
    }

    public function testCreateAdAndFailValidation()
    {
        $newAd = (factory(\App\Ad::class)->make());
        $newAd->title = null;

        $this->post($this->routeUrl, array_merge($newAd->toArray(), $this->authToken));
        $this->assertResponseStatus(\Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testCreateAdAndUpdate()
    {
        $adResponse = $this->testCreateAdAndSuccess();
        $ad = json_decode($adResponse->response->getContent());
        $newPayload = [
            'title' => str_random(10)
        ];
        $this->patch($this->routeUrl."/{$ad->id}", array_merge($newPayload, $this->authToken));
        $this->assertResponseOk();
        $this->seeJson($newPayload);
    }

    public function testFailUpdateNotFound()
    {
        $id = random_int(1000, 3000);
        $this->patch($this->routeUrl."/{$id}", array_merge([], $this->authToken));
        $this->assertResponseStatus(\Illuminate\Http\Response::HTTP_NOT_FOUND);
    }
}
