<li class="nav-item">
    <a type="button" class="nav-link {{ $active ? $active : '' }}" role="tab" data-bs-toggle="tab"
        data-bs-target="#{{ $key }}" aria-controls="{{ $key }}" id="tab-{{ $key }}"
        aria-selected="true">
        {{ $icon ?? '' }} {{ $title }}
    </a>
</li>
