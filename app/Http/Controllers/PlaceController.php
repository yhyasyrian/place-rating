<?php

namespace App\Http\Controllers;

use App\Models\Place;
use Illuminate\Http\Request;
use Artesaos\SEOTools\Traits\SEOTools;
use App\Http\Requests\AddReviewPlaceRequest;
use App\Models\Setting;

class PlaceController extends Controller
{
    use SEOTools;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->seo()->setTitle(Setting::getAll()['title_home']);
        $this->seo()->setDescription(Setting::getAll()['description_home']);
        $this->seo()->opengraph()->setUrl(asset(''));
        $this->seo()->opengraph()->addProperty('type', 'website');
        $this->seo()->jsonLd()->setType('WebPage');
        $places = Place::orderBy('view_count', 'desc')->paginate(12);
        return view('main.home', compact('places'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AddReviewPlaceRequest $request, Place $place)
    {
        if ($place->reviews()->where('user_id', auth()->id())->exists()) {
            return redirect()->back()->with('error', 'لقد قمت بالمراجعة من قبل');
        }
        $place->reviews()->create([
            'review' => $request->comment,
            'service_rating' => $request->get('service_rating', 0),
            'quality_rating' => $request->get('quality_rating', 0),
            'cleanliness_rating' => $request->get('cleanliness_rating', 0),
            'price_rating' => $request->get('price_rating', 0),
            'user_id' => auth()->id(),
        ]);
        return redirect()->back()->with('success', 'تم اضافة المراجعة بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(Place $place)
    {
        $place->view_count++;
        $place->save();
        $this->seo()->setTitle($place->name);
        $this->seo()->setDescription($place->description);
        $this->seo()->opengraph()->setUrl(url()->current());
        $this->seo()->opengraph()->addProperty('type', 'place');
        $this->seo()->jsonLd()->setType('WebPage');
        return view('main.place', ['place' => $place]);
    }
    public function bookmarks()
    {
        $this->seo()->setTitle('المفضلة');
        $this->seo()->setDescription('المفضلة');
        $this->seo()->opengraph()->setUrl(url()->current());
        $this->seo()->opengraph()->addProperty('type', 'website');
        $this->seo()->jsonLd()->setType('WebPage');
        return view('main.category', ['places' => auth()->user()->bookmarks()->paginate(12), 'title' => 'المفضلة']);
    }
    public function topRated()
    {
        $this->seo()->setTitle('الأكثر تقييماً');
        $this->seo()->setDescription('الأكثر تقييماً');
        $this->seo()->opengraph()->setUrl(url()->current());
        $this->seo()->opengraph()->addProperty('type', 'website');
        $this->seo()->jsonLd()->setType('WebPage');
        return view('main.category', ['places' => Place::query()->avgRating()->orderBy('avg', 'desc')->paginate(12), 'title' => 'الأكثر تقييماً']);
    }
    public function topViews()
    {
        $this->seo()->setTitle('الأكثر مشاهدة');
        $this->seo()->setDescription('الأكثر مشاهدة');
        $this->seo()->opengraph()->setUrl(url()->current());
        $this->seo()->opengraph()->addProperty('type', 'website');
        $this->seo()->jsonLd()->setType('WebPage');
        return view('main.category', ['places' => Place::orderBy('view_count', 'desc')->paginate(12), 'title' => 'الأكثر مشاهدة']);
    }
}
