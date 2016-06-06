@extends('admin.setting.index')

@section('sub_content')
    <div class="col-md-9 b-l bg-white bg-auto">
        <div class="p-md bg-light lt b-b font-bold">
            {{ ucwords($currentPage) }}
            <a href="javascript:history.back()" class="btn btn-default pull-right"><i class="glyphicon glyphicon-chevron-left"></i>Back</a>
        </div>
        {!! Form::model($query, ['url' => $url, 'method' => $method, 'class' => 'p-md col-md-6']) !!}

            @include('admin.layouts.error_and_message')

            @foreach($fields as $field => $attr)
                <div class="form-group">
                    <label>{{ ucwords($field) }}</label>
                    <?php
                        $attr = explode('|', $attr);
                        $type = $attr[0];
                        $required = ($attr[1]) ?:null;
                    ?>
                    <input type="{{ $type }}" name="{{ $field }}" class="form-control" value="{{ ($query && $type != 'password') ? $query->$field : ''}}" {{ ($required) ?:'' }}>
                </div>
            @endforeach

            <button type="submit" class="btn btn-info m-t">{{ ($method == 'post') ? 'Submit' : 'Update'}}</button>
        {!! Form::close() !!}
    </div>
@endsection

@push('scripts')
    <script>
        //
    </script>
@endpush
