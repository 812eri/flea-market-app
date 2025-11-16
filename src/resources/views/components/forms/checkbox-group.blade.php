@props(['label', 'name', 'options', 'checked' => []])

<div class="form-group form-group-{{ $name }}">
    <label class="form-label">{{ $label }}</label>
    <div class="checkbox-group-container">
        @foreach ($options as $value => $label)
            @php
                $isChecked = in_array($value, old($name, $checked ?? []));
                @endphp
                <label class="checkbox-item {{ $isChecked ? 'is-checked' : '' }}">
                    <input
                        type="checkbox"
                        name="{{ $name }}[]"
                        value="{{ $value }}"
                        {{ $isChecked ? 'checked' : '' }}
                    >
                    <span class="checkbox-label">{{ $label }}</span>
                </label>
            @endforeach
    </div>

    @error($name)
        <p class="form-error-message">{{ $message }}</p>
    @enderror
</div>