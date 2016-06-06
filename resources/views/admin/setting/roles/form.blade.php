@extends('admin.setting.index')

@section('sub_content')
    <div class="col-md-9 b-l bg-white bg-auto">
        <div class="p-md bg-light lt b-b font-bold">
            {{ ucwords($formAttr['currentPage']) }}
            <a href="javascript:history.back()" class="btn btn-default pull-right"><i class="glyphicon glyphicon-chevron-left"></i>Back</a>
        </div>

        {!! Form::model($formAttr['query'], ['url' => $formAttr['url'], 'method' => $formAttr['method'], 'class' => 'p-md col-md-12']) !!}

            @include('admin.layouts.error_and_message')

            @foreach($formAttr['fields'] as $field => $attr)
                @if ($field == 'permissions')
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
                                        <td class="center">
                                            {!! Form::checkbox('create['.$key.']', 1, (isset($formAttr['query']->permissions['create.'.$key])) ? true : false, ['class' => 'role_check_box']) !!}
                                        </td>
                                        <td class="center">
                                            {!! Form::checkbox('edit['.$key.']', 1, (isset($formAttr['query']->permissions['edit.'.$key])) ? true : false, ['class' => 'role_check_box']) !!}
                                        </td>
                                        <td class="center">
                                            {!! Form::checkbox('delete['.$key.']', 1, (isset($formAttr['query']->permissions['delete.'.$key])) ? true : false, ['class' => 'role_check_box']) !!}
                                        </td>
                                        <td class="center">
                                            {!! Form::checkbox('allow_all['.$key.']', 1, (isset($formAttr['query']->permissions['allow_all.'.$key])) ? true : false, ['class' => 'allow_this_role']) !!}
                                        </td>
                                    </tr>
                                @empty
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="form-group">
                        <label>{{ ucwords($field) }}</label>
                        <?php
                            $attr = explode('|', $attr);
                            $type = $attr[0];
                            $required = ($attr[1]) ?:null;
                        ?>
                        <input type="{{ $type }}" name="{{ $field }}" class="form-control" value="{{ ($formAttr['query'] && $type != 'password') ? $formAttr['query']->$field : ''}}" {{ ($required) ?:'' }}>
                    </div>
                @endif
            @endforeach

            <button type="submit" class="btn btn-info m-t">{{ ($formAttr['method'] == 'post') ? 'Submit' : 'Update'}}</button>
        {!! Form::close() !!}
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // role check boxes
            $('input.allow_all_roles').on('change', function() {
                if($(this)[0].checked == true) {
                    checkedAllRoles(true);
                } else {
                    checkedAllRoles(false);
                }
            });

            $('input.role_check_box').on('change', function() {
                checkedThisRole($(this));
                checkedGroupRoles();
            });

            $('input.allow_this_role').on('change', function() {
                if($(this)[0].checked == true) {
                    checkedAllThisRole($(this), true);
                } else {
                    checkedAllThisRole($(this), false);
                }
                checkedGroupRoles();
            });
        });

        checkedAllRoles = function(checked) {
            checkboxesAction($('input.role_check_box'), checked);
            checkboxesAction($('input.allow_this_role'), checked);
        }

        checkedAllThisRole = function(dom, checked) {
            var checkboxes = dom.closest('tr').find('.role_check_box');
            checkboxesAction(checkboxes, checked);
        }

        checkedGroupRoles = function() {
            var totalGroupChecked = 0;
            var groupRole = $('input.allow_this_role');
            for(var i=0, n=groupRole.length;i<n;i++) {
                if (groupRole[i].checked) totalGroupChecked++;
                else totalGroupChecked--;
            }

            var checked = (totalGroupChecked == groupRole.length) ? true : false;

            $('input.allow_all_roles')[0].checked = checked;
        }

        checkboxesOnload = function() {
            var checkboxes = $('input.role_check_box');
            var checkboxesAllowThis = $('input.allow_this_role');
            var checkTotal = 0;
            for(var i=0, n=checkboxes.length;i<n;i++) {
                if (checkboxes[i].checked) checkTotal++;
                else checkTotal--;
            }
            var checked =  (checkTotal == checkboxes.length) ? true : false;

            if (checked) {
                $('input.allow_all_roles')[0].checked = checked;
                checkboxesAction(checkboxesAllowThis, checked);
            } else {
                for(var i=0, n=checkboxesAllowThis.length;i<n;i++) {
                    var thisTr = $('input.allow_this_role:eq('+i+')').closest('tr');
                    var checkRole = thisTr.find('.role_check_box');

                    checkedThisRole(checkRole);
                }
            }

        }

        checkboxesAction = function(checkboxes, checked) {
            for(var i=0, n=checkboxes.length;i<n;i++) {
                checkboxes[i].checked = checked;
            }
        }

        checkedThisRole = function(dom) {
            var checkedThisRole = dom.closest('tr').find('.role_check_box');
            var allowThisRole = dom.closest('tr').find('.allow_this_role');
            var totalChecked = 0;
            for(var i=0, n=checkedThisRole.length;i<n;i++) {
                if (checkedThisRole[i].checked) totalChecked++;
                else totalChecked--;
            }
            if (totalChecked == 3) {
                allowThisRole[0].checked = true;
            } else {
                allowThisRole[0].checked = false;
            }
        }
    </script>
@endpush
