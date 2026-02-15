@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="page-header">
    <h3 class="page-title">Dashboard</h3>
</div>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">

                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                <h4 class="card-title">Welcome</h4>
                <p class="card-text">
                    You are logged in!
                </p>

            </div>
        </div>
    </div>
</div>

@endsection
