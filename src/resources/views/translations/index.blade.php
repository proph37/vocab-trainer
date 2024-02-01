@extends('layouts.app')

@section('title', 'My Translations')

@section('css_includes')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            {{-- TODO: replicate table behaviour with divs in order to display forms correctly --}}
            <table id="example2" class="table table-bordered table-hover">
                <thead>
                <tr>
                    @foreach($languages as $language)
                        <th>{{ __($language->name) }}</th>
                    @endforeach
                    <th>{{ __('Action') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($meanings as $meaning_id => $translations)
                    <form class="translationForm{{$meaning_id}}" method="POST">
                        @csrf
                        <tr>
                            @foreach($translations as $translation)
                                <td id="{{ $translation->language_name }}">
                                    <input type="text" class="form-control" value="{{ $translation->word_name }}"
                                           aria-label="{{ $translation->word_name }}"
                                           name="{{ $translation->word_id }}">
                                </td>
                            @endforeach
                            <td>
                                <button type="submit" class="btn btn-primary btn-sm updateBtn"
                                        data-meaning-id="{{ $meaning_id }}">
                                    <span class="fa fa-pencil-alt" aria-hidden="true"></span>
                                </button>
                                <button type="submit" class="btn btn-danger btn-sm destroyBtn"
                                        data-meaning-id="{{ $meaning_id }}">
                                    <span class="fas fa-trash" aria-hidden="true"></span>
                                </button>
                            </td>
                        </tr>
                    </form>
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                    @foreach($languages as $language)
                        <th>{{ __($language->name) }}</th>
                    @endforeach
                    <th>{{  __('Action') }}</th>
                </tr>
                </tfoot>
            </table>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
@endsection

@section('js_includes')
    <!-- DataTables  & Plugins -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
@endsection

@section('js_custom')
    <script>
        $(document).ready(function () {
            $(".updateBtn, .destroyBtn").click(function () {
                var meaningId = $(this).data("meaning-id");
                var form = $("form.translationForm" + meaningId);

                // Set the dynamic form action based on the button clicked
                if ($(this).hasClass("updateBtn")) {
                    form.attr("action", "{{ route('translations.update', 'meaningIdPlaceholder') }}"
                        .replace('meaningIdPlaceholder', meaningId));
                    form.append('<input type="hidden" name="_method" value="PATCH">');
                } else if ($(this).hasClass("destroyBtn")) {
                    form.attr("action", "{{ route('translations.destroy', 'meaningIdPlaceholder') }}"
                        .replace('meaningIdPlaceholder', meaningId));
                    form.append('<input type="hidden" name="_method" value="DELETE">');
                }
            });
        });

        // Custom SEARCH for inputs
        $.fn.dataTableExt.ofnSearch['html-input'] = function (value) {
            return $(value).val();
        };

        // Initialize DataTables here
        $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            columnDefs: [
                {
                    "type": "html-input",
                    "targets": Array.from({length: {{ count($translations) }}}, (_, index) => index + 1)
                }
            ],
        });
    </script>
@endsection
