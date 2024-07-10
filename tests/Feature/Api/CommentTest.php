<?php

use App\Models\Comment;
use App\Models\Tweet;
use App\Models\User;

it('allows authenticated users to create a comment', function () {
  $user = User::factory()->create();
  $token = $user->createToken('test_token')->plainTextToken;
  $tweet = Tweet::factory()->create(['user_id' => $user->id]);

  $data = ['comment' => 'This is a new comment'];

  $response = $this->postJson("/api/tweets/{$tweet->id}/comments", $data, [
    'Authorization' => 'Bearer ' . $token
  ]);

  $response->assertStatus(201);
  $response->assertJson(['comment' => 'This is a new comment']);
});

it('displays a list of comments for a tweet', function () {
  $user = User::factory()->create();
  $token = $user->createToken('test_token')->plainTextToken;
  $tweet = Tweet::factory()->create(['user_id' => $user->id]);

  Comment::factory()->count(3)->create(['tweet_id' => $tweet->id]);

  $response = $this->getJson("/api/tweets/{$tweet->id}/comments", [
    'Authorization' => 'Bearer ' . $token
  ]);

  $response->assertStatus(200);
  $response->assertJsonCount(3);
});

it('displays a specific comment', function () {
  $user = User::factory()->create();
  $token = $user->createToken('test_token')->plainTextToken;
  $tweet = Tweet::factory()->create(['user_id' => $user->id]);
  $comment = Comment::factory()->create(['tweet_id' => $tweet->id]);

  $response = $this->getJson("/api/tweets/{$tweet->id}/comments/{$comment->id}", [
    'Authorization' => 'Bearer ' . $token
  ]);

  $response->assertStatus(200);
  $response->assertJson(['id' => $comment->id]);
});

it('allows a user to update their comment', function () {
  $user = User::factory()->create();
  $token = $user->createToken('test_token')->plainTextToken;
  $tweet = Tweet::factory()->create(['user_id' => $user->id]);
  $comment = Comment::factory()->create(['tweet_id' => $tweet->id, 'user_id' => $user->id]);

  $data = ['comment' => 'Updated comment content'];

  $response = $this->putJson("/api/tweets/{$tweet->id}/comments/{$comment->id}", $data, [
    'Authorization' => 'Bearer ' . $token
  ]);

  $response->assertStatus(200);
  $response->assertJson(['comment' => 'Updated comment content']);
});

it('allows a user to delete their comment', function () {
  $user = User::factory()->create();
  $token = $user->createToken('test_token')->plainTextToken;
  $tweet = Tweet::factory()->create(['user_id' => $user->id]);
  $comment = Comment::factory()->create(['tweet_id' => $tweet->id, 'user_id' => $user->id]);

  $response = $this->deleteJson("/api/tweets/{$tweet->id}/comments/{$comment->id}", [], [
    'Authorization' => 'Bearer ' . $token
  ]);

  $response->assertStatus(200);
  $response->assertJson(['message' => 'Comment deleted successfully']);
});

// 更新のテスト（他のユーザのコメントが更新できないことを確認）
it('does not allow unauthorized users to update a comment', function () {
  // ユーザを2人作成
  $owner = User::factory()->create();
  $otherUser = User::factory()->create();

  // 一人目で Tweet と Comment を作成
  $tweet = Tweet::factory()->create(['user_id' => $owner->id]);
  $comment = Comment::factory()->create(['tweet_id' => $tweet->id, 'user_id' => $owner->id]);

  // 二人目で認証
  $token = $otherUser->createToken('test_token')->plainTextToken;

  // 一人目の Comment を二人目で更新（失敗するのが正しい）
  $response = $this->putJson("/api/tweets/{$tweet->id}/comments/{$comment->id}", ['comment' => 'Updated comment'], [
    'Authorization' => 'Bearer ' . $token
  ]);

  $response->assertStatus(403); // Forbidden
});

// 削除のテスト（他のユーザのコメントが削除できないことを確認）
it('does not allow unauthorized users to delete a comment', function () {
  $owner = User::factory()->create();
  $otherUser = User::factory()->create();

  $tweet = Tweet::factory()->create(['user_id' => $owner->id]);
  $comment = Comment::factory()->create(['tweet_id' => $tweet->id, 'user_id' => $owner->id]);

  $token = $otherUser->createToken('test_token')->plainTextToken;

  $response = $this->deleteJson("/api/tweets/{$tweet->id}/comments/{$comment->id}", [], [
    'Authorization' => 'Bearer ' . $token
  ]);

  $response->assertStatus(403); // Forbidden
});
