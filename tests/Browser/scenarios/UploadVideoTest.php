<?php

namespace Tests\Browser\scenarios;

use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Http\UploadedFile;

class UploadVideoTest extends DuskTestCase
{
    public function setUp() {
        parent::setUp();

        exec('php artisan route:scan');
        exec('php artisan migrate --seed');

        $this->browse(function(Browser $browser) {
            $browser->resize(1200, 800);
        });
    }

    public function tearDown() {
        parent::tearDown();

        exec('php artisan migrate:reset');
    }

    /**
     * Permet de vérifier que la modal se ferme après que la requête soit
     * envoyée
     */
    public function testModal_CheckModalClosedCase_Success()
    {
        $file = UploadedFile::fake()->create('video.mp4', 1024);

        $this->browse(function (Browser $browser) use ($file) {
            $browser->loginAs(User::find(2))
                    ->visit('/admin/contents/modify/1')
                    ->click('a[data-target="#uploadModal"]')
                    ->waitFor('.modal')
                    ->assertVisible('.modal')
                    ->assertValue('.modal input[name=id]', 1)
                    ->attach('video', $file)
                    ->value('.modal input[name="title"]', 'Title sample')
                    ->value('.modal textarea[name="description"]', 'Description sample')
                    ->press('Uploader')
                    ->waitUntilMissing('.modal');
        });
    }
}
