@php use App\Constants\RouteNames; @endphp
@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <h1 class="text-left">Custom Jobs</h1>
                <form action="{{ route(RouteNames::LOGOUT_STORE) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger">Logout</button>
                </form>

                <div class="table-responsive">
                    <table class="table text-center">
                        <thead>
                        <tr>
                            <th>Class</th>
                            <th>Method</th>
                            <th>Parameters</th>
                            <th>Delay (seconds)</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Retries</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($customJobs as $customJob)
                            <tr>
                                <td>{{ $customJob->class_name }}</td>
                                <td>{{ $customJob->method }}</td>
                                <td>[{{ implode(',', $customJob->parameters) }}]</td>
                                <td>{{ $customJob->delay }}</td>
                                <td>{{ $customJob->priority }}</td>
                                <td>{{ $customJob->status }}</td>
                                <td>{{ $customJob->retries_executed }}</td>
                            </tr>
                        @empty
                            <tr class="text-center">
                                <td colspan="7">No results found</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="text-center">
                    {{ $customJobs->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection