<form action="{{ route('authentication.logout') }}" method="post">
    @csrf
    <button class="btn btn-default btn-flat" type="submit">
        @lang('layouts.logout')
    </button>
</form>