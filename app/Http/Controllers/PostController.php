<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Inertia\Inertia;
use App\Models\Topic;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Resources\PostResource;
use App\Http\Resources\TopicResource;
use App\Http\Resources\CommentResource;
use Illuminate\Database\Eloquent\Builder;
use Laravel\Scout\Builder as ScoutBuilder;

class PostController extends Controller
{

    function __construct()
    {
        $this->authorizeResource(Post::class);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Topic $topic = null)
    {
        if ($request->query('query')) :
            $posts = Post::search($request->query('query'))
                ->query(fn(Builder $query) => $query->with(['user', 'topic']))
                ->when($topic, fn(ScoutBuilder $query) => $query->where('topic_id', $topic->id));
        else:
            $posts = Post::with(['user', 'topic'])
                ->when($topic, fn($query) => $query->whereBelongsTo($topic))
                ->latest()
                ->latest('id');
        endif;

        return Inertia('Posts/Index', [
            'posts' => PostResource::collection($posts->paginate()->withQueryString()),
            'topics' => fn() => TopicResource::collection(Topic::all()),
            'selectedTopic' => fn() => $topic ? TopicResource::make($topic) : null,
            'query' => $request->query('query'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia('Posts/Create', [
            'topics' => fn() => TopicResource::collection(Topic::all()),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'min:10', 'max:120'],
            'topic_id' => ['required', 'exists:topics,id'],
            'body' => ['required', 'string', 'min:100', 'max:10000'],
        ]);

        $post = Post::create([
            ...$data,
            'user_id' => $request->user()->id
        ]);

        return redirect($post->showRoute());
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Post $post)
    {
        if (! Str::endsWith($post->showRoute(), $request->path())) :
            return redirect($post->showRoute($request->query()), 301);
        endif;

        $post->load('user', 'topic');

        return inertia('Posts/Show', [
            'post' => fn() => PostResource::make($post)->withLikePermission(),
            'comments' => function () use ($post) {
                $CommentResource = CommentResource::collection($post->comments()->with('user')->latest()->latest('id')->paginate(10));

                $CommentResource->collection->transform(fn($request) => $request->withLikePermission());

                return $CommentResource;
            },
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
    }
}
