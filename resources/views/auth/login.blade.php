@php use App\Constants\RouteNames; @endphp
@extends('layouts.app')
@section('content')

    <div class="container">

        @if ($errors->any())
            <div>
                @foreach ($errors->all() as $error)
                    <p style="color: red;">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route(RouteNames::LOGIN_STORE) }}" method="POST">
            @csrf
            <h1 class="h3 mb-3 fw-normal">Sign in</h1>

            <div class="form-floating">
                <input type="email" name="email" class="form-control" id="floatingInput" required>
                <label for="floatingInput">Email address</label>
            </div>
            <div class="form-floating">
                <input type="password" name="password" class="form-control" id="floatingPassword" required>
                <label for="floatingPassword">Password</label>
            </div>

            <button class="w-100 btn btn-lg btn-primary" type="submit">Sign in</button>
        </form>
    </div>
@endsection