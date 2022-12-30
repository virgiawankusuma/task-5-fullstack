@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <h1>Posts</h1>
            <hr>
            <div class="row">
                @foreach ($posts as $post)
                <div class="col-12 col-md-6 my-3">
                    <div class="card">
                        <div class="row g-0">
                            <div class="col-md-4">
                                <img 
                                    src="{{ $post->image }}" 
                                    class="img-fluid rounded-start" 
                                    alt="{{ $post->title }}"
                                >
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $post->title }}</h5>
                                    <div class="position-absolute top-0 end-0 p-2">
                                        <span class="badge text-bg-secondary">{{ $post->category->name }}</span>
                                    </div>
                                    <h6 class="card-subtitle mb-2 text-muted">{{ $post->user->name }}</h6>
                                    <p class="card-text">
                                    {{ Str::limit($post->content, 60, '...') }}
                                    </p>
                                    <div class="d-flex justify-content-start align-items-center gap-2">
                                        <a href="/post/{{$post->id}}/edit" class="btn btn-sm btn-warning">Edit</a>
                                        <form action="/post/{{$post->id}}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    {{-- @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif --}}
</div>
@endsection
