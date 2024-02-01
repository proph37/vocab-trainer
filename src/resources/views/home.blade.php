@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">
                @include('profile_sidebar')
            </div>
            <!-- /.col -->
            <div class="col-md-9">
                <!-- Default box -->
                <div class="card">
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <p>{{ __('First setup languages in') }} <a
                                href="{{ route('profile.index') }}">{{ __('User Profile') }}</a>.</p>
                        <p>{{ __('Then, let\'s do some practice!') }}</p>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
@endsection
