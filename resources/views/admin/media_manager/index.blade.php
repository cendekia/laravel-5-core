@extends('admin.layouts.master')

@section('content')
    <div class="card">
        <div class='card-body'>
            <iframe src="{{ url('admin/media-manager-elfinder') }}" frameborder="0" style="width: 100%;min-height: 420px;"></iframe>
        </div>
    </div>
@endsection
