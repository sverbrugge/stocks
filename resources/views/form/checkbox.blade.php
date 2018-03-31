<div class="form-group form-check">
    <?php $value = isset($value) ? $value : 1; ?>

    @component('form.hidden')
        @slot('field', $field)
        @slot('value', '')
    @endcomponent

    <input
        id="{{ $field }}"
        name="{{ $field }}"
        class="form-check-input"
        type="checkbox"
        value="{{ $value }}"
        {{ (old($field) == $value) || (isset($checked) && $checked) ? 'checked' : '' }}
        {{ trim($slot) }}
    />
    <label class="form-check-label" for="{{ $field }}">
        {{ $label }}
    </label>
</div>
