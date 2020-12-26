<script defer>
    window.config = `@json($organization_config)`

</script>

<script src="{{ asset('accounting/js/rsvp.min.js')}}" defer></script>

<script defer>
    window.reusable_translator = `@json(trans('reusable'))`;
    window.messages = `@json(trans('messages'))`
</script>


<script src="{{ asset('js/app.js') }}" defer></script>
<script defer src="{{ asset('accounting/js/font_awesome.js')}}"></script>