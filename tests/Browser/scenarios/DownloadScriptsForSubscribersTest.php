<?php

namespace Tests\Browser\scenarios;

use App\Models\Content;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class DownloadScriptsForSubscribersTest extends DuskTestCase
{
    private $content;
    private $user;

    public function setUp() {
        parent::setUp();

        exec('php artisan migrate:reset');
        exec('php artisan route:scan');
        exec('php artisan migrate');

        factory(User::class)->create([
            User::$ROLE => 'customer'
        ]);

        factory(User::class, 'admin')->create()->each(function($user) {
            $this->user = $user;

            factory(Content::class, 1)->make()->each(function(Content $content) use ($user) {
                $user->contents()->save($content);

                $content->sources()->create([
                    'name' => 'file-3.zip'
                ]);

                $this->content = $content;
            });
        });
    }

    public function tearDown() {
        parent::tearDown();

        exec('php artisan migrate:reset');
    }

    /**
     * Permet de tester qu'une personne non connectée est dirigée vers
     * la page de login
     */
    public function testDownloadSources_NotLoggedCase_RedirectToLoginPage() {
        $content = Content::find(1);

        $this->browse(function (Browser $browser) use ($content) {
            $browser->visit('/tutorials/' . $content[Content::$SLUG])
                ->assertSee($content[Content::$TITLE])
                ->click('header a.btn')
                ->assertPathIs('/authentication');
        });
    }

    /**
     * Permet de tester le fichier est téléchargé lors du clique que la ressource
     * pour un utilisateur membre de type "customer"
     */
    public function testDownloadSources_AuthenticatedUserCase_FileDownloaded() {
        $content = Content::find(1);
        $user = User::where(User::$ROLE, 'customer')->first();

        $this->browse(function (Browser $browser) use ($content, $user) {
            $browser->loginAs($user)
                ->visit('/tutorials/' . $content[Content::$SLUG])
                ->assertSee($content[Content::$TITLE])
                ->click('header a.btn')
                ->assertPathIs('/authentication')
                ->assertSee('Vous devez posséder un abonnment pour télécharger les ressources du site.');
        });
    }
}
