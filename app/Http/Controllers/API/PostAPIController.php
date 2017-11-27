<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\APIBaseController as APIBaseController;
use App\Post;
use Validator;

class PostAPIController extends APIBaseController
{
    /**
     * Show all posts.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $posts = Post::all();
        return $this->sendResponse($posts->toArray(), 'Posts retrieved successfully.');
    }

    /**
     * Store a post.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required',
            'description' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $post = Post::create($input);

        return $this->sendResponse($post->toArray(), 'Post created successfully.');
    }

    /**
     * Show a single post.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $post = Post::find($id);

        if (is_null($post)) {
            return $this->sendError('Post not found.');
        }

        return $this->sendResponse($post->toArray(), 'Post retrieved successfully.');
    }

    /**
     * Update a post.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
            'description' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $post = Post::find($id);
        if (is_null($post)) {
            return $this->sendError('Post not found.');
        }
        $post->name = $input['name'];
        $post->description = $input['description'];
        $post->save();

        return $this->sendResponse($post->toArray(), 'Post updated successfully.');
    }

    public function destroy($id)
    {
        $post = Post::find($id);

        if (is_null($post)) {
            return $this->sendError('Post Not Found.');
        }

        $post->delete();

        return $this->sendResponse($id, 'Post deleted successfuly');
    }
}