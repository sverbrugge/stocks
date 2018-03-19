@auth
<li class="nav-item {{ Route::currentRouteNamed('stocks.*') ? 'active' : '' }}">
	<a class="nav-link" href="{{ route('stocks.index') }}">
		@lang('Stocks')
	</a>
</li>

<li class="nav-item {{ Route::currentRouteNamed('shares.*') ? 'active' : '' }}">
	<a class="nav-link" href="{{ route('shares.index') }}">
		@lang('Shares')
	</a>
</li>

<li class="nav-item {{ Route::currentRouteNamed('dividends.*') ? 'active' : '' }}">
	<a class="nav-link" href="{{ route('dividends.index') }}">
		@lang('Dividends')
	</a>
</li>

<li class="nav-item {{ Route::currentRouteNamed('gains') ? 'active' : '' }}">
	<a class="nav-link" href="{{ route('gains') }}">
		@lang('Gains')
	</a>
</li>

<li class="nav-item {{ Route::currentRouteNamed('exchanges.*') ? 'active' : '' }}">
	<a class="nav-link" href="{{ route('exchanges.index') }}">
		@lang('Exchanges')
	</a>
</li>

<li class="nav-item {{ Route::currentRouteNamed('currencies.*') ? 'active' : '' }}">
	<a class="nav-link" href="{{ route('currencies.index') }}">
		@lang('Currencies')
	</a>
</li>
@endauth
