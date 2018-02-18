<?php

namespace App\Http\Controllers;

use App\Favorite;
use App\Reply;

class FavoritesController extends Controller
{
    /**
     * FavoritesController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @param Reply $reply
     * @return mixed
     */
    public function store(Reply $reply)
    {
        $reply->favorite();

        return back();
    }
}
