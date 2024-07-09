<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tweet;
use App\Services\TweetService;

class TweetController extends Controller
{
  protected $tweetService;

  public function __construct(TweetService $tweetService)
  {
    $this->tweetService = $tweetService;
  }

  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $tweets = $this->tweetService->allTweets();
    return response()->json($tweets);
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $request->validate([
      'tweet' => 'required|max:255',
    ]);
    $tweet = $this->tweetService->createTweet($request->only('tweet'), $request->user());
    return response()->json($tweet, 201);
  }

  /**
   * Display the specified resource.
   */
  public function show(Tweet $tweet)
  {
    return response()->json($tweet);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, Tweet $tweet)
  {
    $request->validate([
      'tweet' => 'required|string|max:255',
    ]);

    $updatedTweet = $this->tweetService->updateTweet($tweet, $request->all());

    return response()->json($tweet);
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Tweet $tweet)
  {
    $this->tweetService->deleteTweet($tweet);
    return response()->json(['message' => 'Tweet deleted successfully']);
  }
}
