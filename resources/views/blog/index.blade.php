@extends('layouts.store')

@section('content')
<div class="max-w-7xl mx-auto px-4 lg:px-8 py-12">
    <h1 class="font-display text-5xl mb-12">Journal</h1>
    <div class="grid md:grid-cols-3 gap-8">
        @foreach($posts as $post)
            <a href="{{ route('blog.show', $post) }}" class="group">
                <div class="aspect-[16/10] bg-zinc-100 overflow-hidden mb-4">
                    <img src="{{ $post->featured_image ? asset('storage/'.$post->featured_image) : 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=600&q=80' }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition duration-700" alt="">
                </div>
                <p class="text-xs uppercase tracking-widest text-zinc-500 mb-2">{{ $post->blogCategory?->name }}</p>
                <h2 class="text-lg font-medium group-hover:text-zinc-300 transition">{{ $post->title }}</h2>
                <p class="text-sm text-zinc-500 mt-2 line-clamp-2">{{ $post->excerpt }}</p>
            </a>
        @endforeach
    </div>
    <div class="mt-10">{{ $posts->links() }}</div>
</div>
@endsection
