@props(['type' => '🍃', 'class' => 'text-2xl'])

@php
    // Map legacy string types to emojis for backward compatibility
    $legacyMap = [
        'leaf' => '🍃',
        'flower' => '🌸',
        'water' => '🌊',
        'sun' => '☀️',
        'moon' => '🌙',
        'star' => '⭐'
    ];
    $display = $legacyMap[$type] ?? $type;
    
    // Default to leaf if somehow empty
    if (empty($display)) {
        $display = '🍃';
    }
@endphp

<span class="{{ $class }} flex items-center justify-center leading-none select-none">
    {{ $display }}
</span>
