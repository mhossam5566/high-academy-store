@php
    $map = [
        'pending' => 'warning',
        'approved' => 'success',
        'rejected' => 'danger',
    ];
    $key = is_string($status) ? $status : (is_object($status) && method_exists($status, 'value') ? $status->value : '');
    $color = $map[$key] ?? 'secondary';
@endphp
<span class="badge bg-{{ $color }}">{{ isset($label) ? $label : $key }}</span>
