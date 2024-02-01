@extends('layouts.app')

@section('title', 'User Profile')

@section('css_includes')
    <!-- Include the plugin's CSS and JS: -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap-multiselect.css') }}">
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">
                @include('profile_sidebar')
            </div>
            <!-- /.col -->
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills">
                            <li class="nav-item"><a class="nav-link active" href="#settings"
                                                    data-toggle="tab">{{ __('Settings') }}</a></li>
                        </ul>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="active tab-pane" id="settings">
                                <form class="form-horizontal" action="{{ route('profile.update', Auth::user()->id) }}"
                                      method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group row">
                                        <label for="inputFirstName"
                                               class="col-sm-2 col-form-label">{{ __('First Name') }}</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="inputFirstName"
                                                   placeholder="First Name" name="first_name"
                                                   value="{{ Auth::user()->first_name }}" readonly>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="inputLastName"
                                               class="col-sm-2 col-form-label">{{ __('Last Name') }}</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="inputLastName"
                                                   placeholder="Last Name" name="last_name"
                                                   value="{{ Auth::user()->last_name }}" readonly>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="inputEmail"
                                               class="col-sm-2 col-form-label">{{ __('Email') }}</label>
                                        <div class="col-sm-10">
                                            <input type="email" class="form-control" id="inputEmail" placeholder="Email"
                                                   name="email" value="{{ Auth::user()->email }}" readonly>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="inputRole" class="col-sm-2 col-form-label">{{ __('Role') }}</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="inputRole" placeholder="Role"
                                                   name="role_name" value="{{ Auth::user()->role->name }}" readonly>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="selectNativeLanguage"
                                               class="col-sm-2 col-form-label">{{ __('Native Language') }}</label>
                                        <div class="col-sm-10">
                                            <select id="selectNativeLanguage" name="native_language_id">
                                                @foreach($languages as $language)
                                                    <option value="{{ $language->id }}"
                                                            @if($language->native_language) selected @endif>{{ $language->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="selectLanguages"
                                               class="col-sm-2 col-form-label">{{ __('Languages') }}</label>
                                        <div class="col-sm-10">
                                            <select id="selectLanguages" multiple="multiple" name="language_ids[]">
                                                @foreach($languages as $language)
                                                    @if(!$language->native_language)
                                                        <option value="{{ $language->id }}"
                                                                @if($language->selected) selected @endif>{{ $language->name }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="offset-sm-2 col-sm-10">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- /.tab-pane -->
                        </div>
                        <!-- /.tab-content -->
                    </div><!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
@endsection

@section('js_includes')
    <!-- Include the plugin's CSS and JS: -->
    <script type="text/javascript" src="{{ asset('js/bootstrap-multiselect.js') }}"></script>
@endsection

@section('js_custom')
    <script>
        $(document).ready(function () {
            $('#selectLanguages').multiselect({
                //includeSelectAllOption: true,
                enableFiltering: true,
                enableCaseInsensitiveFiltering: true,
                filterPlaceholder: 'Search Here..',
                allSelectedText: false
            });
        });

        $(document).ready(function () {
            $('#selectNativeLanguage').multiselect({
                //includeSelectAllOption: true,
                enableFiltering: true,
                enableCaseInsensitiveFiltering: true,
                filterPlaceholder: 'Search Here..'
            });
        });
    </script>
@endsection

