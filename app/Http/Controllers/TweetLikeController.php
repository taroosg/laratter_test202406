<?php

namespace App\Http\Controllers;

use App\Models\Tweet;
use Illuminate\Http\Request;
use App\Services\TweetLikeService;

class TweetLikeController extends Controller
{

  protected $tweetLikeService;

  public function __construct(TweetLikeService $tweetLikeService)
  {
    $this->tweetLikeService = $tweetLikeService;
  }

  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    //
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Tweet $tweet)
  {
    $this->tweetLikeService->likeTweet($tweet, auth()->user());
    return back();
  }

  /**
   * Display the specified resource.
   */
  public function show(string $id)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(string $id)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, string $id)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Tweet $tweet)
  {
    $this->tweetLikeService->dislikeTweet($tweet, auth()->user());
    return back();
  }
}
