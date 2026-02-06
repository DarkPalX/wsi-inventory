@extends('theme.layouts.report')

@section('pagecss')
@endsection

@section('content')
    <div class="wrapper p-5">
        
        <div class="row">

            <div class="col-md-12 text-center">
                <h4 class="text-uppercase">{{ $page->name }}<br><small>{{ Setting::info()->company_name }}</small></h4>
            </div>
            
        </div>
        
        <div class="row mt-5">
            <div>
                <form class="d-flex align-items-center" id="searchForm" style="margin-bottom:10px;font-size: 12px !important;">
                    <input type="hidden" name="is_search" id="is_search" value="1">
                    <table width="100%" style="margin-bottom: 0px;">
                        <tr style="font-size:12px;font-weight:bold;">
                            <td>Role</td>
                            <td colspan="3">Search</td>
                        </tr>
                        <tr>
                            <td>
                                <select name="role" id="role" class="form-control" style="font-size:12px;">
                                    <option value="">- All -</option>
                                    @php $roles = \App\Models\Role::orderBy('name')->get(); @endphp
                                    @forelse($roles as $role)
                                        <option value="{{$role->id}}" @if(isset($_GET['role']) && $_GET['role']==$role->id) selected @endif>{{$role->name}}</option>
                                    @empty
                                    @endforelse
                                </select>
                            </td>
                            <td width="30%"><input name="search" type="search" id="search" class="form-control" placeholder="Search by Name or Email"  @if(isset($_GET['search'])) value="{{$_GET['search']}}" @endif style="font-size:12px;"></td>
                            <td>
                                <input type="submit" class="btn text-light" value="Search" style="font-size:12px; background-color: #3d80e3;">
                            </td>
                           
                        </tr>
                        <tr><td><a href="{{route('reports.users')}}" style="font-size:12px;">Reset Filter</a></td></tr>
                    </table>
                </form>
            </div>

            <div class="table-responsive-faker">
                <table id="report" class="table table-hover" cellspacing="0" width="100%">
                    <thead class="table-secondary">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rs as $r)
                            <tr id="row{{$r->id}}">
                                <td><strong>{{ $r->name }}</strong></td>
                                <td>{{ $r->email }}</td>
                                <td>{{ User::userRole($r->role_id) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center text-danger p-5" colspan="100%">No item available</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                
                <div class="row">
                    {{-- <div class="col-md-12">
                        {{ $rs->onEachSide(1)->links('pagination::bootstrap-5') }}
                    </div> --}}
                </div>

            </div>

        </div>

    </div>

@endsection

@section('pagejs')
     <script>
        var target_cols = [];
    </script>
@endsection