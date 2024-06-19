<div class="tab-pane fade show {{ $active ?? '' }}" id="{{ $key }}" aria-labelledby="tab-{{ $key }}"
    role="tabpanel">
    {{ $slot }}
</div>
