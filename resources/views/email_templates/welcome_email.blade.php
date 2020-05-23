@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Welcome {{$name}}</h2>

    <p>Please <a href="{{$verificationLink}}" target="_blank">click here</a> to confirm your account or directly opy and paste below link in new tab.</p>

    <p>{{$verificationLink}}</p>
</div>
@endsection
