@auth
    @include('layouts.header-authenticated')
@else
    @include('layouts.header-guest')
@endauth