@extends('admin.layouts.master')

@section('content')

    @include('admin.layouts.error_and_message')

    <div class="panel panel-default">
        <div class="panel-heading" style="min-height: 65px;">
            <h4 class="pull-left">{{ ucwords($pageTitle) }}</h4>
            @if ($actionButtons['create'])
                <a href="{{ $url . '/create' }}" class="btn btn-success pull-right">Add new</a>
            @endif
        </div>

        <div class="table-responsive">

            <table class="table table-striped table-hover bg-white " id="{{ str_slug($pageTitle) }}">
                <thead>
                    <tr>
                    @foreach($datatableColumns as $column)
                        <th style="{{ ($column['name'] == 'action') ? 'width:65px' : '' }}">{{ strtoupper(str_replace('_', ' ', $column['name'])) }}</th>
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
