<script defer>
    window.config = `@json($organization_config)`

</script>

<script src="https://cdn.jsdelivr.net/npm/rsvp@4/dist/rsvp.min.js" defer></script>

<script defer>
    window.reusable_translator = `@json(trans('reusable'))`;
    window.messages = `@json(trans('messages'))`
</script>


<script src="{{ asset('js/app.js') }}" defer></script>
<script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>