@props(['name', 'label', 'options' => []])

<div class="form-group">
    <label for="{{ $name }}">{{ $label }}</label>

    <select
        {{ $attributes->merge([
            'id' => $name,
            'name' => $name,
            'class' => 'form-select'
            ]) }}
    >
            <option value="" disabled selected>選択してください</option>

            @foreach ($options as $value => $text)
                <option value="{{ $value }}" {{ old($name) == $value ? 'selected' : '' }}>
                    {{ $text }}
                </option>
            @endforeach
    </select>

    @error($name)
            <p class="error-message">{{ $message }}</p>
    @enderror
</div>