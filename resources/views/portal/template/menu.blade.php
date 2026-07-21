<li>
    <a class="@if(isset($active) && $active=='dashboard') {{'active'}} @endif"
        href="{{url('portal')}}">
        <span>Dashboard</span>
    </a>
</li>

{{--
    This package only ships fixed User/Role modules out of the box.
    Apps built on this package should override this file
    (resources/views/portal/template/menu.blade.php in the app itself)
    with their own module links — the app's copy always wins over this one.
--}}

@if(Route::has('user.master') && \Access::is_allowed('user','list'))
<li class="menu-divider">Administration</li>
@endif

@if(Route::has('user.master') && \Access::is_allowed('user','list'))
<li>
    <a href="{{route('user.master')}}" class="@if(isset($active) && $active=='user') {{'active'}} @endif">
        <span>Users</span>
    </a>
</li>
@endif

@if(Route::has('role.master') && \Access::is_allowed('role','list'))
<li>
    <a href="{{route('role.master')}}" class="@if(isset($active) && $active=='role') {{'active'}} @endif">
        <span>Roles &amp; Permissions</span>
    </a>
</li>
@endif
