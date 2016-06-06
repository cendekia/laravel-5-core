@if (session('status'))
    <div class="alert alert-success">
        {!! session('status') !!}
    </div>
@endif
@if (count($errors) > 0)
    <div class="alert alert-danger">
        @if(count($errors) == 1)
            @foreach ($errors->all() as $error)
                {!! $error !!}
            @endforeach
        @else
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{!! $error !!}</li>
                @endforeach
            </ul>
        @endif
    </div>
@endif
