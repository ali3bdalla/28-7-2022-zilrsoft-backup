<script defer>
    window.config = `@json($organization_config)`

</script>

<script defer>
    window.reusable_translator = `@json(trans('reusable'))`;
    window.messages = `@json(trans('messages'))`
</script>


<script src="{{ asset('js/app.js') }}" defer></script>
<script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>