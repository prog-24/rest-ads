<?php

namespace App\Http\Controllers;

use App\Ad;
use Illuminate\Http\Request;

class AdController extends Controller
{
    /**
     * @var Ad
     */
    private $ad;
    private $authUser;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Ad $ad)
    {
        //
        $this->ad = $ad;
        $this->authUser = \Auth::user();
    }

    public function index()
    {
        $ads = $this->ad->newQuery()->orderByDesc('id')->get();

        return $ads;
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'description' => 'sometimes',
            'price' => 'required|numeric'
        ]);

        $ad = $this->ad;
        $ad->title = $request->title;
        $ad->description = $request->description ?? null;
        $ad->price = $request->price;
        $ad->user()->associate($this->authUser);

        $ad->save();
        return $ad;
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'sometimes',
            'description' => 'sometimes',
            'price' => 'sometimes|numeric'
        ]);

        $ad = $this->ad->newQuery()->forUser($this->authUser->id)->where('id', $id)->firstOrFail();

        $ad->update($request->only(['title', 'description', 'price']));

        return $ad;
    }

    //
}
