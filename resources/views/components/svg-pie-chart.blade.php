@props(['watched' => 0, 'total' => 0])

@php
    $percentage = $total > 0 ? round(($watched / $total) * 100) : 0;
    $radius = 45;
    $circumference = 2 * pi() * $radius;
    $watchedStroke = ($percentage / 100) * $circumference;
    $notWatchedStroke = $circumference - $watchedStroke;
@endphp

<div style="display: flex; align-items: center; justify-content: center; flex-direction: column;">
    <svg width="150" height="150" viewBox="0 0 100 100">
        <!-- Background circle -->
        <circle cx="50" cy="50" r="{{ $radius }}" fill="transparent" stroke="#e5e7eb" stroke-width="10" />

        <!-- Watched part of the circle -->
        <circle cx="50" cy="50" r="{{ $radius }}" fill="transparent" stroke="#34d399" stroke-width="10"
                stroke-dasharray="{{ $watchedStroke }} {{ $notWatchedStroke }}"
                stroke-dashoffset="0" transform="rotate(-90 50 50)" />

        <!-- Text in the middle -->
        <text x="50" y="50" font-family="Verdana" font-size="12" text-anchor="middle" alignment-baseline="middle">{{ $percentage }}%</text>
    </svg>
    <div style="display: flex; justify-content: center; margin-top: 1rem;">
        <div style="display: flex; align-items: center; margin-right: 1rem;">
            <span style="width: 12px; height: 12px; background-color: #34d399; display: inline-block; margin-right: 0.5rem;"></span>
            <span>Watched: {{ $watched }}</span>
        </div>
        <div style="display: flex; align-items: center;">
            <span style="width: 12px; height: 12px; background-color: #e5e7eb; display: inline-block; margin-right: 0.5rem;"></span>
            <span>Not Watched: {{ $total - $watched }}</span>
        </div>
    </div>
</div>
