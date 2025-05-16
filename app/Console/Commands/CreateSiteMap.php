<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Place;
use App\Models\Category;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use Illuminate\Console\Command;

class CreateSiteMap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-site-map';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a sitemap for the application';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Sitemap::create()
            ->add(Url::create(asset('')))
            ->add(Category::all())
            ->add(Place::all())
            ->writeToFile(public_path('sitemap.xml'));
    }
}
