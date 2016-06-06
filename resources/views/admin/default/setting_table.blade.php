@extends('admin.setting.index')

@section('sub_content')
    <div class="col-md-9 b-l bg-white bg-auto">
        <div class="p-md bg-light lt b-b font-bold">{{ ucwords($currentPage) }}</div>

        <div class="table-responsive" style="padding: 25px;">

            @include('admin.layouts.error_and_message')

            <table class="table table-bordered table-hover bg-white" id="{{ str_slug($currentPage) }}">
                <thead>
                    <tr>
                    @foreach($datatableColumns as $column)
                        <th>{{ ucfirst(str_replace('_', ' ', $column['name'])) }}</th>
                    @endforeach
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            $('#' + '{{ str_slug($currentPage) }}').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! $tableUrl !!}',
                columns: webarq.datatable_columns
            });
        });
    </script>
@endpush
