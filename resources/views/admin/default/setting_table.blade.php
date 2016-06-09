@extends('admin.setting.index')

@section('sub_content')
    <div class="col-md-9 b-l bg-white bg-auto">
        <div class="p-md bg-light lt b-b font-bold">
            {{ ucwords($pageTitle) }}
            @if ($actionButtons['create'])
                <a href="{{ $url . '/create' }}" class="btn btn-success pull-right">Add new</a>
            @endif
        </div>

        <div class="table-responsive" style="padding: 25px;">

            @include('admin.layouts.error_and_message')

            <table class="table table-striped table-hover bg-white " id="{{ str_slug($pageTitle) }}">
                <thead>
                    <tr>
                    @foreach($datatableColumns as $column)
                        <th style="{{ ($column['data'] == 'action') ? 'width:65px' : '' }}">{{ strtoupper(str_replace('_', ' ', $column['data'])) }}</th>
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
            $('#' + '{{ str_slug($pageTitle) }}').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! $url . '/ajax' !!}',
                columns: webarq.datatable_columns
            });
        });
    </script>
@endpush
