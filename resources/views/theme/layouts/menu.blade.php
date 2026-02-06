@php
    $menu = Menu::where('is_active', 1)->first();
@endphp


<ul class="menu-container">
    @foreach ($menu->parent_navigation() as $item)
        @include('theme.layouts.menu-item', ['item' => $item])
    @endforeach
    
    @if(session('member_login_session') == 'active')
        <li class="menu-item {{ request()->is('portal*') ? 'current' : '' }}">
            <a href="{{ env('APP_URL') . '/portal' }}" class="menu-link">
                <div>
                    Portal
                </div>
            </a>
        </li>
    @endif


</ul>

