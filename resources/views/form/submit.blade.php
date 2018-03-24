<div class="form-group">
    <button type="submit" class="btn btn-primary">
        @lang(trim($slot) ? trim($slot) : 'Submit')
    </button>

    @if(isset($return_route))
        <a role="button" class="btn btn-secondary" href="{{ $return_route }}">
            @lang(isset($return_label) ? $return_label : 'Cancel')
        </a>
    @endif
</div>
