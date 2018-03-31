@component('form.group')
    @slot('field', $field)
    @slot('label', $label)

    @slot('input')
        <div class="input-group">
            @if(isset($prepend) and $prepend)
                <div class="input-group-prepend">
                    <span class="input-group-text">{{ $prepend }}</span>
                </div>
            @endif
            <input
                id="{{ $field }}"
                name="{{ $field }}"
                value="{{ old($field, isset($value) ? $value : '') }}"
                type="{{ isset($type) ? $type : 'text' }}"
                class="form-control{{ $errors->has($field) ? ' is-invalid' : '' }}"
                aria-describedby="{{ $field . 'HelpBlock' }}"
                placeholder="{{ isset($placeholder) ? __($placeholder) : '' }}"
                {!! $slot !!}
            />
            @if(isset($append) and $append)
                <div class="input-group-append">
                    <span class="input-group-text">{{ $append }}</span>
                </div>
            @endif
        </div>
    @endslot

    @slot('help')
        {{ isset($help) ? __($help) : '' }}
    @endslot
@endcomponent
