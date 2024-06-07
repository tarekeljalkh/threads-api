<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use Illuminate\Http\Request;

class FriendController extends Controller
{
    public function follow_and_unfollow($following_id)
    {
        try {
            $check_if_follow = Friend::whereFollowingId($following_id)->exists();
            if ($check_if_follow) {
                Friend::whereFollowingId($following_id)->delete();

                return response([
                    'message' => 'Unfollow',
                ], 201);
            }else{
                Friend::create([
                    'following_id' => $following_id,
                    'follower_id' => auth()->id(),
                ]);

                return response([
                    'message' => 'Follow'
                ], 200);
            }
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
