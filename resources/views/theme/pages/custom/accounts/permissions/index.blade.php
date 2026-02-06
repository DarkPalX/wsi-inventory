@extends('theme.main')

@section('pagecss')
@endsection

@section('content')
    <div class="wrapper p-5">
        
        <div class="row">

            <div class="col-md-6">
                <strong class="text-uppercase">{{ $page->name }}</strong>

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item">{{ $page->name }}</li>
                        <li class="breadcrumb-item">Manage</li>
                    </ol>
                </nav>
                
            </div>
            
        </div>

        <form method="post" action="{{ route('accounts.permissions.update-permissions') }}">
            @csrf

            <div class="row mt-4 mb-3">
                <div class="col-md-12 d-flex align-items-center justify-content-end">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save Changes</button>
                </div>
            </div>
            
            <div class="row">
                <div class="table-responsive-faker" style="background-color: aliceblue;">

                    <table id="permissions_tbl" class="table table-hover" cellspacing="0" width="100%">
                        <thead class="table-secondary">
                            <tr>
                                <th>Module</th>
                                @foreach($roles as $role)
                                    <th>{{ $role->name }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($modules as $module)
                            
                                <tr class="fw-bold">
                                    <td>{{ $module['name'] }}</td>
                                    @foreach($roles as $role)                                
                                        <td><input type="checkbox" class="select-all-role" data-role="{{ $role->name }}" data-module="{{ $module['name'] }}"> <i class="fa-solid fa-check-double"></i></td>
                                    @endforeach
                                </tr>

                                @foreach($module['permissions'] as $permission)
                                    <tr>
                                        <td><i class=""></i>{{ $permission['name'] }}</td>
                                        @foreach($roles as $role)
                                            @php($is_checked = "")

                                            @forelse($role_permissions as $role_permission)
                                                @if($role_permission->module_id == $module['id'] && $role_permission->role_id == $role->id && $role_permission->permission_id == $permission['id'])
                                                    @php($is_checked = "checked")
                                                    @break
                                                @endif
                                            @empty
                                            @endforelse

                                            <td><input name="module_role_permission[]" value="[{{ $module['id'] }},{{ $role->id }},{{ $permission['id'] }}]" type="checkbox" class="module-checkbox" data-role="{{ $role->name }}" data-module="{{ $module['name'] }}" {{ $is_checked }}></td>
                                        @endforeach
                                    </tr>
                                @endforeach

                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>

        </form>

    </div>


@endsection

@section('pagejs')
	
    <script>
        $('.select-all-role').on('click', function () {
            var role = $(this).data('role');
            var module = $(this).data('module');
            var checkboxes = $('input.module-checkbox[data-role="' + role + '"][data-module="' + module + '"]');
            checkboxes.prop('checked', $(this).prop('checked'));
        });
    </script>

@endsection