@extends('layouts.app')
@section('title')
  @if($post)
    {{ $post->title }}
    @if(!Auth::guest() && $post->author_id == Auth::user()->id)
      <button class="btn" style="float: right"><a href="{{ url('edit/'.$post->slug)}}">Edit Post</a></button>
    @endif
  @else
    Page does not exist
  @endif
@endsection
@section('title-meta')
<p>{{ $post->created_at->format('M d,Y \a\t h:i a') }} By <a href="{{ url('/user/'.$post->author_id)}}">{{ $post->author->name }}</a></p>
@endsection
@section('content')
@if($post)
<div>
  {!! $post->body !!}
</div>
<div>
  <h2>Rate the post</h2>
</div>
@if(Auth::guest())
<p>Login to Rate the post</p>
@else
<div class="panel-body">
  <form class="form-horizontal poststars" action="{{route('postRating', $post->id)}}" id="addStar" method="POST">
    {{ csrf_field() }}
    <div class="form-group required">
      <div class="col-sm-12">
        <div class="col-sm-4">
          
          @for ($i = 1; $i <= 5; $i++)
            @if($i <= (int)$rating)
              <input class="star star-{{$i}}" value="{{$i}}" id="star-{{$i}}" type="radio" name="star"/>
              <label class="star star-{{$i}} rated" for="star-{{$i}}"></label>
            @else
              <input class="star star-{{$i}}" value="{{$i}}" id="star-{{$i}}" type="radio" name="star"/>
              <label class="star star-{{$i}} unrated" for="star-{{$i}}"></label>
            @endif
          @endfor
        </div>
        <div class="col-sm-4 text">
          <span>Rating: {{ (int)$rating }}</span>
        </div>
        <div class="col-sm-4">
          
        </div>
      </div>
      
    </div>
  </form>
</div>
@endif
<div>
  <h2>Leave a comment</h2>
</div>
@if(Auth::guest())
<p>Login to Comment</p>
@else
<div class="panel-body">
  <form method="post" action="/comment/add">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="on_post" value="{{ $post->id }}">
    <input type="hidden" name="slug" value="{{ $post->slug }}">
    <div class="form-group">
      <textarea required="required" placeholder="Enter comment here" name = "body" class="form-control"></textarea>
    </div>
    <input type="submit" name='post_comment' class="btn btn-success" value = "Post"/>
  </form>
</div>
@endif
<div>
  @if($comments)
  <ul style="list-style: none; padding: 0">
    @foreach($comments as $comment)
    <li class="panel-body">
      <div class="list-group">
        <div class="list-group-item">
          <h3>{{ $comment->author->name }}</h3>
          <p>{{ $comment->created_at->format('M d,Y \a\t h:i a') }}</p>
        </div>
        <div class="list-group-item">
          <p>{{ $comment->body }}</p>
        </div>
      </div>
    </li>
    @endforeach
  </ul>
  @endif
</div>
@else
404 error
@endif
@endsection