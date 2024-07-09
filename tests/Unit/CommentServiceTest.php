<?php

use App\Models\User;
use App\Models\Tweet;
use App\Models\Comment;
use App\Services\CommentService;
use Illuminate\Http\Request;
use Mockery as m;

// コメント作成のテスト
it('creates a new comment', function () {
  $user = User::factory()->create();
  $tweet = Tweet::factory()->create(['user_id' => $user->id]);
  $commentService = new CommentService();
  $commentData = ['comment' => 'Test comment'];

  // Requestオブジェクトをモックする
  $request = m::mock(Request::class);
  $request->shouldReceive('all')->andReturn($commentData);
  $request->shouldReceive('comment')->andReturn('Test comment');
  $request->shouldReceive('user')->andReturn((object)['id' => $user->id]);

  $comment = $commentService->createComment($request, $tweet);

  expect($comment)->toBeInstanceOf(Comment::class);
  expect($comment->comment)->toEqual('Test comment');
  expect($comment->tweet_id)->toEqual($tweet->id);
  expect($comment->user_id)->toEqual($user->id);
});

// コメント一覧取得のテスト
it('retrieves all comments for a tweet', function () {
  $tweet = Tweet::factory()->create();
  Comment::factory()->count(3)->create(['tweet_id' => $tweet->id]);
  $commentService = new CommentService();

  $comments = $commentService->tweetComments($tweet);

  expect($comments)->toHaveCount(3);
});

// コメント更新のテスト
it('updates a comment', function () {
  $comment = Comment::factory()->create();
  $commentService = new CommentService();
  $updatedData = ['comment' => 'Updated comment'];

  // Requestオブジェクトをモックする
  $request = m::mock(Request::class);
  $request->shouldReceive('only')->with('comment')->andReturn(['comment' => 'Updated comment']);

  $updatedComment = $commentService->updateComment($request, $comment);

  expect($updatedComment->comment)->toEqual('Updated comment');
});

// コメント削除のテスト
it('deletes a comment', function () {
  $comment = Comment::factory()->create();
  $commentService = new CommentService();

  $commentService->deleteComment($comment);

  $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
});
