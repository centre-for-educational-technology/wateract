<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        return Inertia::render('News/Index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        $this->authorize('create', News::class);

        if (Auth::user()) {
            return Inertia::render('News/Create', []);
        }
        return NewsController::index();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $this->authorize('create', News::class);

        Validator::make($request->all(), [
            'body_text' => 'required',
        ])->validateWithBag('addNews');

        $request['user_id'] = Auth::id();
        News::create($request->all());
        return redirect()->route('news.index')
            ->with('success', __('springs.messages.news_added'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function show(News $news)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\News  $news
     * @return \Inertia\Response
     */
    public function edit(News $news)
    {
        $this->authorize('update', $news);

        if (Auth::user()) {
            return Inertia::render('News/Edit', [
                'news' => $news ]);
        }
        return NewsController::index();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, News $news)
    {
        $this->authorize('update', $news);

        Validator::make($request->all(), [
            'body_text' => 'required',
        ])->validateWithBag('editNews');

        $news->update($request->all());

        return redirect()->route('news.index')
            ->with('success', __('springs.messages.news_updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\News $news
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(News $news)
    {
        $this->authorize('delete', $news);

        $news->delete();

        return redirect()->route('news.index')
            ->with('success', __('springs.messages.news_deleted'));
    }

    public function getNews(Request $request)
    {
        $locale =  $request['locale'];
        $order_by = "created_at";
        if ($request['order_by']) {
            $order_by = $request['order_by'];
        }
        $news = News::where('visible', 1)
            ->where('locale', $locale)
            ->orderBy($order_by, 'desc')
            ->with('user');
        return $news->paginate(5);
    }
}
