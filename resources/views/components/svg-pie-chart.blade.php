@props(['watched' => 0, 'total' => 0])

@php
    $percentage = $total > 0 ? ($watched / $total) * 100 : 0;
    $circumference = 2 * pi() * 45;
    $offset = $circumference - ($percentage / 100) * $circumference;
@endphp

<div style="position: relative; display: flex; align-items: center; justify-content: center;">
    <svg style="transform: rotate(-90deg);" width="120" height="120" viewBox="0 0 100 100">
        <circle stroke-width="10" stroke="#E5E7EB" fill="transparent" r="45" cx="50" cy="50"/>
        <circle stroke-width="10"
                stroke-dasharray="{{ $circumference }}"
                stroke-dashoffset="{{ $offset }}"
                stroke-linecap="round"
                stroke="#3B82F6"
                fill="transparent"
                r="45"
                cx="50"
                cy="50"/>
    </svg>
    <div style="position: absolute; font-size: 1.25rem; font-weight: bold;">
        {{ $watched }} / {{ $total }}
    </div>
</div>
