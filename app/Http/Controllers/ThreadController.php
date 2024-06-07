<?php

namespace App\Http\Controllers;

use App\Http\Requests\ThreadRequest;
use App\Http\Resources\ThreadResource;
use App\Models\Comment;
use App\Models\Like;
use App\Models\SubComment;
use App\Models\Thread;
use Illuminate\Http\Request;

class ThreadController extends Controller
{

    public function index()
    {
        try {
            $threads = Thread::with('user')->with('likes')->with('comments')->latest()->get();
            $threads = ThreadResource::collection($threads);
            return response([
                'threads' => $threads
            ], 201);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(ThreadRequest $threadRequest)
    {
        try {
            $threadRequest->validated();

            $data = [
                'body' => $threadRequest->body
            ];

            //check image
            if ($threadRequest->hasFile('image')) {
                $threadRequest->validate([
                    'image' => 'image',
                ]);

                $imagePath = 'public/images/threads';
                $image = $threadRequest->file('image');
                $image_name = $image->getClientOriginalName();
                $path = $threadRequest->file('image')->storeAs($imagePath, rand(0, 0) . $image_name);
                $data['image'] = $path;
            }

                    // Retrieve the authenticated user
        $user = auth()->user();
        // Use the retrieved user to create the thread
        $thread = $user->threads()->create($data);

            //$save = auth()->user()->threads()->create($data);

            if ($thread) {
                return response([
                    'message' => 'Success',
                ], 201);
            } else {
                return response([
                    'message' => 'Error'
                ], 500);
            }
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function react($thread_id)
    {
        try {
            $thread = Like::whereThreadId($thread_id)->whereUserId(auth()->id())->first();
            if($thread){
                Like::whereThreadId($thread_id)->whereUserId(auth()->id())->delete();
                return response([
                    'message' => 'Unliked'
                ], 200);
            }else{
                Like::create([
                    'user_id' => auth()->id(),
                    'thread_id' => $thread_id,
                ]);
                return response([
                    'message' => 'Liked',
                ], 201);
            }
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function comment(Request $request)
    {
        try {
            $request->validate([
                'thread_id' => 'required|integer',
                'body' => 'required|max:255'
            ]);

            $comment = Comment::create([
                'user_id' => auth()->id(),
                'thread_id' => $request->thread_id,
                'body' => $request->body,
            ]);

            if($comment){
                return response([
                    'message' => 'Success',
                ], 201);
            }else{
                return response([
                    'message' => 'Error'
                ], 500);
            }
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function subcomment(Request $request)
    {
        try {
            $request->validate([
                'comment_id' => 'required|integer',
                'body' => 'required|max:255'
            ]);

            $subcomment = SubComment::create([
                'user_id' => auth()->id(),
                'comment_id' => $request->comment_id,
                'body' => $request->body,
            ]);

            if($subcomment){
                return response([
                    'message' => 'Success',
                ], 201);
            }else{
                return response([
                    'message' => 'Error'
                ], 500);
            }
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage()
            ], 500);
        }
    }

}


// <?php

// namespace App\Http\Controllers;

// use App\Http\Requests\ThreadRequest;
// use App\Models\Thread;
// use Illuminate\Http\Request;

// class ThreadController extends Controller
// {
//     public function store(ThreadRequest $threadRequest)
//     {
//         try {
//             // Retrieve the authenticated user
//             $user = auth()->user();

//             // Check if the user is authenticated
//             if (!$user) {
//                 return response(['message' => 'Unauthenticated'], 401);
//             }

//             // Validate request data
//             $threadRequest->validated();

//             $data = [
//                 'body' => $threadRequest->body,
//             ];

//             // Handle file upload if an image is provided
//             if ($threadRequest->hasFile('image')) {
//                 $threadRequest->validate([
//                     'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Image validation rules
//                 ]);

//                 $imagePath = 'public/images/threads';
//                 $image = $threadRequest->file('image');
//                 $image_name = time() . '_' . $image->getClientOriginalName(); // Use time to avoid name collisions
//                 $path = $image->storeAs($imagePath, $image_name);

//                 $data['image'] = $path;
//             }

//             // Save the new thread using the user's threads relationship
//             $thread = $user->threads()->create($data);

//             if ($thread) {
//                 return response([
//                     'message' => 'Thread created successfully',
//                     'thread' => $thread
//                 ], 201);
//             } else {
//                 return response([
//                     'message' => 'Error saving thread'
//                 ], 500);
//             }
//         } catch (\Exception $e) {
//             return response([
//                 'message' => 'An error occurred: ' . $e->getMessage(),
//             ], 500);
//         }
//     }
// }
