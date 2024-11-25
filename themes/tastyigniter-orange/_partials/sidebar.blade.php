<!-- Sidebar Navigation -->
<div class="sidebar navbar-light bg-light border-end d-flex flex-column vh-100 p-3">
    <button
        class="navbar-toggler border-0 mb-3 d-lg-none"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#navbarMainHeader"
        aria-controls="navbarMainHeader"
        aria-expanded="false"
        aria-label="Toggle navigation"
    >
        <span class="navbar-toggler-icon"></span>
    </button>

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
