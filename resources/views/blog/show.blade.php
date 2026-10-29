@extends('layouts.store')

@section('content')
<article class="max-w-3xl mx-auto px-4 py-12">
    <p class="text-xs uppercase tracking-widest text-zinc-500 mb-4">{{ $blog->blogCategory?->name }} · {{ $blog->published_at?->format('M d, Y') }}</p>
    <h1 class="font-display text-4xl lg:text-5xl mb-8">{{ $blog->title }}</h1>
    <div class="prose prose-lg max-w-none text-zinc-700">
        {!! $blog->content !!}
    </div>
    <a href="{{ route('blog.index') }}" class="inline-block mt-12 text-xs uppercase tracking-widest text-zinc-500 hover:text-zinc-900">← Back to Journal</a>
</article>
@endsection
