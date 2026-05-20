@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm t-text']) }}>
    {{ $value ?? $slot }}
</label>
