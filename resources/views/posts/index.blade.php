@extends('layouts.app')

@section('content')

	<h3>Post</h3>



	@if(count($posts) > 0)
		@foreach($posts as $post)
			<div class="card card-header" style="margin-bottom: 20px">
				<div class="row">
					<div class="col-md-4 col-sm-4">
						<img style="width: 100%; height: 200px; object-fit: cover;" src="storage/cover_images/{{$post->cover_image}}">
					</div>
					<div class="col-md-8 col-sm-8">
						<h2> <a href="/posts/{{$post->id}}">{{ $post->title }}</a></h2>
						<small>Written on {{ $post->created_at }} by {{$post->user->name}}</small>
					</div>
				</div>
			</div>
		@endforeach
		{{-- Pagination After foreach and use ->links() --}}
		{{ $posts->links() }} 
	@else
		<p>No Record</p>
	@endif



@endsection