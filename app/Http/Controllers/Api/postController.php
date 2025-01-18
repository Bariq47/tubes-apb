<?php

namespace App\Http\Controllers\Api;


use App\Models\post;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class postController extends Controller
{
    public function index()
    {
        //fungsinya untuk get all post
        $posts = Post::latest()->paginate(5);

        return new PostResource(true, 'list data post', $posts);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:png,jpg,jpeg,gif,svg|max:2048',
            'title' => 'required',
            'content' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //upload image
        $image = $request->file('image');
        $image->storeAs('public/posts', $image->hashName());

        //create post
        $posts = post::create([
            'image' => $image->hashName(),
            'title' => $request->title,
            'content' => $request->content,
        ]);

        //return reponse
        return new PostResource(true, 'Data post berhasil ditambahkan', $posts);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'content' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $posts = Post::find($id);

        if ($request->hasFile('image')) {

            //upload image
            $image = $request->file('image');
            $image->storeAs('public/posts', $image->hashName());

            Storage::delete('public/posts/' . basename($posts->image));

            $posts->update([
                'image'     => $image->hashName(),
                'title'     => $request->title,
                'content'   => $request->content,
            ]);
        } else {
            $posts->update([
                'title'     => $request->title,
                'content'   => $request->content,
            ]);
        }

        return new PostResource(true, 'Data post berhasil ditambahkan', $posts);
    }

    public function destroy($id)
    {
        $post = Post::find($id);

        //delete image
        Storage::delete('public/posts/' . basename($post->image));
        $post->delete();

        return new PostResource(true, 'data post berhasil dihapus', null);
    }

    public function show($id)
    {
        $post = Post::find($id);
        return new PostResource(true, 'Detail Data!', $post);
    }
}
