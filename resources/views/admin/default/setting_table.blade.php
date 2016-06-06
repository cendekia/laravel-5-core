@extends('admin.setting.index')

@section('sub_content')
    <div class="col-md-9 b-l bg-white bg-auto">
        <div class="p-md bg-light lt b-b font-bold">{{ ucwords($tableName) }}</div>
        <div class="table-responsive" style="padding: 25px;">
            <table class="table table-bordered table-hover bg-white" id="{{ str_slug($tableName) }}">
                <thead>
                    <tr>
                    @foreach($datatableColumns as $column)
                        <th>{{ $column['name'] }}</th>
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
            $('#' + '{{ str_slug($tableName) }}').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! $tableUrl !!}',
                columns: webarq.datatable_columns
            });
        });
    </script>
@endpush
