@component('form.group')
    @slot('field', $field)
    @slot('label', $label)

    @slot('input')
        <input
            id="{{ $field }}"
            name="{{ $field }}"
            value="{{ isset($value) ? $value : '' }}"
            type="{{ isset($type) ? $type : 'text' }}"
            class="form-control{{ $errors->has($field) ? ' is-invalid' : '' }}"
            aria-describedby="{{ $field . 'HelpBlock' }}"
            placeholder="{{ isset($placeholder) ? __($placeholder) : '' }}"
            {!! $slot !!}
        />
    @endslot

    @slot('help')
        {{ isset($help) ? __($help) : '' }}
    @endslot
@endcomponent
