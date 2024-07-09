<?php

namespace App\Services;

use App\Models\Tweet;

class TweetService
{
  /**
   * Create a new class instance.
   */
  public function __construct()
  {
    //
  }

  public function createTweet($data, $user)
  {
    return $user->tweets()->create($data);
  }

  public function allTweets()
  {
    return Tweet::with('user')->latest()->get();
  }

  public function updateTweet(Tweet $tweet, $data)
  {
    $tweet->update($data);
    return $tweet;
  }

  public function deleteTweet(Tweet $tweet)
  {
    return $tweet->delete();
  }
}
