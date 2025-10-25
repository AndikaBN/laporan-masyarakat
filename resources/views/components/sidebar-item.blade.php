@props(['active' => false])

<li>
    <a
        href="{{ $href ?? '#' }}"
        @class([
            'sidebar-menu-item',
            'active' => $active,
        ])
    >
        {{ $slot }}
    </a>
</li>

<style>
    .sidebar-menu-item {
        display: block;
        padding: 12px 20px;
        color: #333;
        text-decoration: none;
        border-left: 4px solid transparent;
        transition: all 0.3s;
        font-size: 14px;
    }

    .sidebar-menu-item:hover {
        background: #f5f5f5;
        border-left-color: #667eea;
    }

    .sidebar-menu-item.active {
        background: #f0f0f0;
        color: #667eea;
        border-left-color: #667eea;
        font-weight: 600;
    }
</style>
