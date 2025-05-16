<?php

namespace App\Http\Controllers;

use App\Models\Place;
use Illuminate\Http\Request;
use Artesaos\SEOTools\Traits\SEOTools;
class SearchController extends Controller
{
    use SEOTools;
    public function __invoke(Request $request) {
        $search = str($request->search)->trim()->replace(['_','%'],'')->toString();
        $this->seo()->setTitle('Search ' . $search);
        $this->seo()->setDescription('Search ' . $search);
        $this->seo()->opengraph()->setUrl(url()->current());
        $this->seo()->opengraph()->addProperty('type', 'website');
        $this->seo()->jsonLd()->setType('WebPage');
        $places = Place::where('name', 'like', '%' . $search . '%')
            ->orderBy('view_count', 'desc')
            ->paginate(12);
        return view('main.search', ['places' => $places]);
    }
}

