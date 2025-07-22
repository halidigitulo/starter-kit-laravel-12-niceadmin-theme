{{-- <ul class="nav flex-column">
@foreach (getAccessibleMenus() as $menu)
    <li class="nav-item">
        <a class="nav-link" href="{{ url($menu->url) }}">
            <i class="bi {{ $menu->icon }}"></i> {{ $menu->name }}
        </a>
        @if ($menu->children->count())
            <ul class="nav flex-column ms-3">
                @foreach ($menu->children as $child)
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url($child->url) }}">
                            <i class="bi {{ $child->icon }}"></i> {{ $child->name }}
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    </li>
@endforeach
</ul> --}}


<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!-- User details -->
        <div class="user-profile text-center mt-3">
            <div class="">
                @if ($user && $user->avatar)
                    <img src="{{ asset('uploads/users/' . $user->avatar) }}" alt=""
                        class="avatar-md rounded-circle">
                @endif
            </div>
            <div class="mt-3">
                <h4 class="font-size-16 mb-1">{{ Auth::user()->name }}</h4>
                <span class="text-muted"><i class="ri-record-circle-line align-middle font-size-14 text-success"></i>
                    Online</span>
            </div>
        </div>

        <!--- Sidemenu -->
        {{-- <div id="sidebar-menu">
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title">Menu</li>

                @foreach (getAccessibleMenus() as $menu)
                    @php
                        $hasChildren = $menu->children->count() > 0;
                        $isActive = request()->is(trim($menu->url, '/')) ? 'active' : '';
                        $isSubActive = $menu->children->contains(function ($child) {
                            return request()->is(trim($child->url, '/'));
                        });
                    @endphp

                    <li
                        class="{{ $hasChildren ? 'has-submenu' : '' }} {{ $isActive || $isSubActive ? 'mm-active' : '' }}">
                        <a href="{{ $hasChildren ? '#menu-' . $menu->id : url($menu->url) }}"
                            class="waves-effect {{ $hasChildren ? 'has-arrow' : '' }}"
                            @if ($hasChildren) aria-expanded="{{ $isSubActive ? 'true' : 'false' }}" @endif>
                            <i class="ri-{{ $menu->icon }}"></i>
                            <span>{{ $menu->name }}</span>
                        </a>

                        @if ($hasChildren)
                            <ul class="sub-menu collapse {{ $isSubActive ? 'show' : '' }}"
                                id="menu-{{ $menu->id }}">
                                @foreach ($menu->children as $child)
                                    <li>
                                        <a href="{{ url($child->url) }}"
                                            class="{{ request()->is(trim($child->url, '/')) ? 'active' : '' }}">
                                            {{ $child->name }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div> --}}

        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title">Menu</li>

                @foreach (getAccessibleMenus() as $menu)
                    @php
                        $isActive = request()->is(ltrim($menu->url, '/')) ? 'active' : '';
                        $hasChildren = $menu->children->count() > 0;
                    @endphp

                    <li class="{{ $hasChildren ? 'has-submenu' : '' }}">
                        <a href="{{ $hasChildren ? '#menu-' . $menu->id : url($menu->url) }}"
                            class="waves-effect {{ $isActive }} {{ $hasChildren ? 'has-arrow' : '' }}"
                            @if ($hasChildren) data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="menu-{{ $menu->id }}" @endif>
                            <i class="ri-{{ $menu->icon }}"></i>
                            <span>{{ $menu->name }}</span>
                        </a>

                        @if ($hasChildren)
                            <ul class="sub-menu collapse" id="menu-{{ $menu->id }}">
                                @foreach ($menu->children as $child)
                                    <li>
                                        <a href="{{ url($child->url) }}"
                                            class="{{ request()->is(ltrim($child->url, '/')) ? 'active' : '' }}">
                                            {{ $child->name }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->
