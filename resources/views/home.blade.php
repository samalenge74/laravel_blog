<?php use \App\Http\Controllers\PostController; ?>
@extends('layouts.app')
@section('title')
{{$title}}
@endsection
@section('content')
@if (!$posts->count())
There is no post to display. Login to start posting!!!
@else
<div class="">
    @foreach( $posts as $post )
    <div class="list-group">
        <div class="list-group-item">
            <h3><a href="{{ url('/'.$post->slug) }}">{{ $post->title }}</a>
                <div class="col-md-12">
                    <div class="stars">
                        @for ($i = 1; $i <= 5; $i++)
                        @if($i <=  PostController::getAVGRating($post))
                            <span class="glyphicon glyphicon-star" style="font-size:15px;color:blue"></span>
                        @else
                            <span class="glyphicon glyphicon-star-empty" style="font-size:15px"></span>
                        @endif
                        @endfor
                    </div>
                </div>
                @if(!Auth::guest() && ($post->author_id == Auth::user()->id || Auth::user()->is_admin()))
                @if($post->active == '1')
                <button class="btn" style="float: right"><a href="{{ url('edit/'.$post->slug)}}">Edit Post</a></button>
                @else
                <button class="btn" style="float: right"><a href="{{ url('edit/'.$post->slug)}}">Edit Draft</a></button>
                @endif
                @endif
            </h3>
            <p>{{ $post->created_at->format('M d,Y \a\t h:i a') }} By <a href="{{ url('/user/'.$post->author_id)}}">{{ $post->author->name }}</a></p>
        </div>
        <div class="list-group-item">
            <article>
                {!! Str::limit($post->body, $limit = 1500, $end = '....... <a href='.url("/".$post->slug).'>Read More</a>') !!}
            </article>
        </div>
    </div>
    @endforeach
    {!! $posts->render() !!}
</div>
@endif
@endsection
