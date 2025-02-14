<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\MasterData\NewsRepository;

class NewsController extends Controller
{
    protected $newsRepository;

    public function __construct(NewsRepository $newsRepository)
    {
        $this->newsRepository = $newsRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return inertia('User/News/Index', [
            'news' => $this->newsRepository->getAllPaginatedWithParams($request, 20)
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        if(!$this->newsRepository->find($id)) return abort('404', 'uppss....');

        $news = $this->newsRepository->find($id);

        return inertia('User/News/Show', [
            'news' => $this->newsRepository->find($id),
        ]);
    }
}
