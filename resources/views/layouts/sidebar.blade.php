<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">
        {{-- <li class="nav-heading">Pages</li> --}}

        @foreach (getAccessibleMenus() as $menu)
            @php
                $isActive = request()->is(ltrim($menu->url, '/')) ? 'active' : '';
                $hasChildren = $menu->children->count() > 0;
            @endphp

            <li class="nav-item">
                @if ($hasChildren)
                    @php
                        $childActive = $menu->children->contains(function ($child) {
                            return request()->is(ltrim($child->url, '/')) ||
                                request()->is(ltrim($child->url, '/') . '/*');
                        });
                    @endphp
                    <a class="nav-link {{ $childActive ? 'active' : 'collapsed' }}"
                        data-bs-target="#menu-{{ $menu->id }}" data-bs-toggle="collapse" href="#">
                        <i class="ri ri-{{ $menu->icon }}"></i>
                        <span>{{ $menu->name }}</span>
                        <i class="bi bi-chevron-down ms-auto"></i>
                    </a>
                    <ul id="menu-{{ $menu->id }}" class="nav-content collapse {{ $childActive ? 'show' : '' }}"
                        data-bs-parent="#sidebar-nav">
                        @foreach ($menu->children as $child)
                            <li>
                                <a href="{{ url($child->url) }}"
                                    class="{{ request()->is(ltrim($child->url, '/')) ? 'active' : '' }}">
                                    <i class="ri ri-circle-line"></i>
                                    <span>{{ $child->name }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <a class="nav-link {{ $isActive ? 'active' : 'collapsed' }}" href="{{ url($menu->url) }}">
                        <i class="ri ri-{{ $menu->icon }}"></i>
                        <span>{{ $menu->name }}</span>
                    </a>
                @endif
            </li>
        @endforeach

    </ul>
</aside>
