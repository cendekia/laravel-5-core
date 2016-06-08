@extends('admin.setting.index')

@section('sub_content')
    <div class="col-md-9 b-l bg-white bg-auto">
        <div class="p-md bg-light lt b-b font-bold">
            {{ ucwords($pageTitle) }}
            <a href="javascript:history.back()" class="btn btn-default pull-right"><i class="glyphicon glyphicon-chevron-left"></i>Back</a>
        </div>
        {!! Form::model($query, ['url' => $url, 'method' => $method, 'class' => 'p-md col-md-12', 'enctype' => 'multipart/form-data']) !!}

            @include('admin.layouts.error_and_message')

            @foreach($fields as $field => $attr)
                <?php
                    $attr = explode('|', $attr);
                    $type = $attr[0];
                    $required = (isset($attr[1])) ? $attr[1] : null;
                    $label = (isset($attr[2])) ? $attr[2] : $field;
                    $dataKey = (isset($attr[3])) ? $attr[3] : null;
                ?>
                <div class="form-group">
                    <label>{{ ucwords(str_replace('_', ' ', $label)) }}</label>
                    @if($type == 'select')
                        {!! Form::select($field, isset($data[$dataKey]) ? $data[$dataKey] : [], null, ['placeholder' => 'Select...', 'class' => 'form-control', 'required' => $required]); !!}
                    @else
                        <input type="{{ $type }}" name="{{ $field }}" class="form-control" value="{{ ($query && $type != 'password') ? $query->$field : ''}}" {{ ($required) ?:'' }}>
                    @endif
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
