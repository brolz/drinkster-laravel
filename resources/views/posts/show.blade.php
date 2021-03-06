@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            <img src="https://drinkster-images.s3-us-west-1.amazonaws.com/uploads/{{ $post->image }}" class="w-100">
        </div>
        <div class="col-md-4">
            <div class="d-flex justify-content-center">
                <div class="d-flex justify-content-center pt-3">
                    <div class="pr-4">
                        <h3 class="card-title display-5">{{ $post->title }}</h3>
                    </div>
                    <div>
                    @can('update', $post)
                        <a class="pl-1" href="/p/{{$post->id}}/edit"><strong>Edit</strong></a>
                    @endcan

                    </div>
                </div>
            </div>
            <hr>
            <div class="d-flex">
                <div class="pr-2">
                    <img src="https://drinkster-images.s3-us-west-1.amazonaws.com/profilePic/{{ $post->user->profile->image }}" class="w-100 rounded-circle" style="max-width: 7rem">
                </div>
                <div class="d-flex align-items-center">
                    <div>
                        <a href="/profile/{{ $post->user->profile->user_id }}"><strong><span class="text-dark pr-1">{{ $post->user->username }}</span></strong></a>
                        <p>{{ $post->caption }}</p>
                    </div>
                </div>
            </div>
            <hr/>
            @if ($post->ingredients)
                <div>
                    <h3>
                        Ingredients
                    </h3>
                    <span>{{ $post->ingredients }}</span>
                </div>
                <hr/>
            @else
                <div>
                    <h3>
                        Ingredients
                    </h3>
                    <p>Worem ipsum dolor sit, amet consectetur adipisicing elit. Dolores odit adipisci modi dicta at aliquam ducimus facilis repellat laudantium, est esse? Commodi delectus possimus at excepturi illo nulla fugiat odit!</p>
                </div>
                <hr/>
            @endif

            @if ($post->instructions)
                <div>
                    <h3>
                        Instructions
                    </h3>
                    <p>{{ $post->instructions }}</p>
                </div>
                <hr/>
            @else
                <div>
                    <h3>
                        Instructions
                    </h3>
                    <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Dolores odit adipisci modi dicta at aliquam ducimus facilis repellat laudantium, est esse? Commodi delectus possimus at excepturi illo nulla fugiat odit!</p>
                </div>
                <hr/>
            @endif
        </div>
    </div>
</div>
@endsection
