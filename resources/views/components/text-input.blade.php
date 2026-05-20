@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-th-border-strong t-surface t-text focus:border-th-primary focus:ring-th-primary rounded-md shadow-sm']) }}>

