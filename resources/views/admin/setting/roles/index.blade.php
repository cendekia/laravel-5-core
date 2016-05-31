@extends('admin.setting.index')

@section('sub_content')
	<div class="col-md-9 b-l bg-white bg-auto">
		<div class="p-md bg-light lt b-b font-bold">Roles</div>

		<div class="panel panel-card">
			<!-- <div class="panel-heading">

            </div>
			<div class="panel-body">

            </div> -->
			<div class="table-responsive">
				<table class="table table-bordered table-hover bg-white">
					<thead>
                        <tr>
                            <th rowspan="2" >FEATURES</th>
                            <th colspan="4" class="text-center">ACCESS RIGHTS</th>
                        </tr>
                        <tr>
                            <th class="center">CREATE</th>
                            <th class="center">EDIT</th>
                            <th class="center">DELETE</th>
                            <th class="center">SELECT ALL {!! Form::checkbox('allow_all[all]', 1, false, ['class' => 'allow_all_roles']) !!}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse (\Admin::adminRouteList() as $key => $route)
                            <tr>
                                <td>{{ ucwords(str_replace('-', ' ', $key)) }}</td>
                                <td class="center"> {!! Form::checkbox('create['.$key.']', 1, false, ['class' => 'role_check_box']) !!}</td>
                                <td class="center"> {!! Form::checkbox('edit['.$key.']', 1, false, ['class' => 'role_check_box']) !!}</td>
                                <td class="center"> {!! Form::checkbox('delete['.$key.']', 1, false, ['class' => 'role_check_box']) !!}</td>
                                <td class="center"> {!! Form::checkbox('allow_all['.$key.']', 1, false, ['class' => 'allow_this_role']) !!}</td>
                            </tr>
                        @empty
                        @endforelse
                    </tbody>
				</table>
			</div>
		</div>

	</div>
@endsection