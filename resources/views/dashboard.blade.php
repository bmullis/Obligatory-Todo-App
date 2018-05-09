@extends('layouts.app', ['title' => 'Dashboard'])

@section('content')
    <div class="dashboard">
        <div class="container">
            <dashboard-tasks></dashboard-tasks>
        </div>
    </div>
@endsection
