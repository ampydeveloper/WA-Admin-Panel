@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Hello, {{$user->first_name.' '. $user->last_name}}</h2>

    <p>Your password has been reset. Please use new password as follows for login:</p>
    <h3>Password:- {{$password}}</h3>
    <br/><hr/>
    <small>Don't forgot to change your password after login.</small>
</div>
@endsection