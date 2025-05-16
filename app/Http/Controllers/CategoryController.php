<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Artesaos\SEOTools\Traits\SEOTools;
class CategoryController extends Controller
{
    use SEOTools;
    public function show(Category $category)
    {
        $places = $category->places()->orderBy('view_count', 'desc')->paginate(12);
        $this->seo()->setTitle($category->name);
        $this->seo()->setDescription($category->description);
        $this->seo()->opengraph()->setUrl(asset('category/' . $category->slug));
        $this->seo()->opengraph()->addProperty('type', 'category');
        $this->seo()->jsonLd()->setType('WebPage');
        return view('main.category', compact('places','category'));
    }
}
