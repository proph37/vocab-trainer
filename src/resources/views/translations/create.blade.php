@extends('layouts.app')

@section('title', 'New Translation')

@include('notification')

<!--

args:
    - languages : collection[Language]
    - native_language : Language
    - word_conflict : bool

-->
@section('content')
    <!-- TODO -->
    @if($word_conflict)
        <!-- Modal -->
        <div class="modal fade" id="translationModal" tabindex="-1" role="dialog" aria-labelledby="translationModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">{{ __('Existing Translations') }}</h4>
                    </div>
                    <div class="modal-body">
                        <div class="box">
                            <div class="box-header">
                                <h3 class="box-title">{{ __('Choose if any of the following translations apply') }}</h3>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body no-padding">
                                <table class="table">
                                    <!-- get all languages -->
                                    <tr>
                                        <th style="width: 10%">#</th>
                                        @foreach($languages as $language)
                                            <th>{{ $language->name }}</th>
                                        @endforeach
                                        <th>{{ __('Action') }}</th>
                                    </tr>
                                    @foreach($meanings as $meaning_id => $translations)
                                        <form action="{{ route('translations.update', $meaning_id) }}" method="post">
                                            @csrf
                                            @method('PATCH')
                                            <tr>
                                                <td>{{ $translation_ctr++ }}</td>
                                                @foreach($translations as $language_name => $word_name)
                                                    <td name="{{ $language_name }}">{{ $word_name }}</td>
                                                @endforeach
                                                <td>
                                                    <button type="submit" class="btn btn-default btn-success btn-sm">
                                                        <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                                                    </button>
                                                </td>
                                            </tr>
                                        </form>
                                    @endforeach
                                </table>
                            </div>
                            <!-- /.box-body -->
                        </div>
                        <!-- /.box -->
                    </div>
                    <div class="modal-footer">
                        <form action="{{ route('translations.store') }}" method="POST">
                            {{ csrf_field() }}
                            @foreach($lang_word as $lang => $word)
                                <input type="hidden" name="{{ $lang }}" value="{{ $word }}">
                            @endforeach
                            <button type="submit" class="btn btn-default" aria-label="Force new entry"
                                    name="force_store" value="1">
                                {{ __('New Entry') }}
                            </button>
                            <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- TODO: put into textarea -->
    @if($word_conflict)
        {{ $lang_word[$language->name] }}
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('translations.store') }}" method="POST" name="translation">
                {{ csrf_field() }}
                @foreach($languages as $language)
                    <div class="form-group">
                        <label for="{{ $language->id }}">{{ $language->name }}:</label>
                        <input type="text" class="form-control" id="{{ $language->id }}" name="{{ $language->id }}">
                    </div>
                @endforeach
                <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
            </form>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
@endsection

@if ($word_conflict)
    @section('js_includes')
        <script>
            $('#translationModal').modal('show');
        </script>
    @endsection
@endif
