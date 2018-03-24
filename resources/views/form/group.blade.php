<div class="form-group">
    <label for="{{ $field }}">
        @lang($label)
    </label>

    {!! $input !!}

    @if(isset($help))
        <div id="{{ $field . 'HelpBlock' }}" class="form-text text-muted">
            {{ $help }}
        </div>
    @endif

    @if($errors->has($field))
        <div class="invalid-feedback">
            {{ $errors->first($field) }}
        </div>
    @endif
</div>
