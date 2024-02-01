@extends('layouts.app')

@section('title', 'Random Translations')

@section('css_includes')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <table id="example2" class="table table-bordered table-hover" wire:init="$dispatch('table-loaded')">
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
                    <livewire:quiz-row :$meaning_id :$translations :key=$meaning_id />
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                    @foreach($languages as $language)
                        <th>{{ __($language->name) }}</th>
                    @endforeach
                    <th>{{ __('Action') }}</th>
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
