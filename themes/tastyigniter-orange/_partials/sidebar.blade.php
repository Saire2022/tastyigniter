<!-- Sidebar Navigation -->
<div class="sidebar-local navbar-light bg-light border-end d-flex flex-column vh-100 p-3">
    <div class="collapse navbar-collapse show" id="navbarMainHeader">
        <ul class="nav flex-column">
            @foreach ($mainMenu->menuItems() as $navItem)
                @continue(Auth::isLogged() && in_array($navItem->code, ['login', 'register']))
                @continue(!Auth::isLogged() && in_array($navItem->code, ['account', 'recent-orders']))
                <li @class(['nav-item', 'dropdown' => $navItem->items])>
                    <a
                        @class(['nav-link', 'dropdown-toggle' => $navItem->items, 'active text-primary' => $navItem->isActive || $navItem->isChildActive])
                        href="{{ $navItem->items ? '#' : $navItem->url }}"
                        @if ($navItem->items) data-bs-toggle="dropdown" @endif
                        {!! $navItem->extraAttributes !!}
                    >
                        @lang($navItem->title)
                    </a>
                    @if ($navItem->items)
                        <ul class="dropdown-menu">
                            @foreach ($navItem->items as $item)
                                <li>
                                    <a
                                        @class(['dropdown-item', 'active' => $item->isActive])
                                        href="{{ $item->url }}"
                                        {!! $item->extraAttributes !!}
                                    >
                                        @lang($item->title)
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
</div>
