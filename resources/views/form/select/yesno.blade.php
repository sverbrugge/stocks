@component('form.select')
    @slot('field', $field)
    @slot('label', $label)
    @slot('value', $value)
    @slot('options', [
        ['id' => 1, 'name' => __('Yes')],
        ['id' => 0, 'name' => __('No')],
    ])
    {{ $slot }}
@endcomponent