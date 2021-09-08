@extends('layouts.web')

@section('content')
    @inertia
    <!--Start of Tawk.to Script-->
    <!-- <script type="text/javascript">
      var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();
      (function () {
        var s1 = document.createElement('script'), s0 = document.getElementsByTagName('script')[0]
        s1.async = true
          @if(app()->isLocale('ar'))
            s1.src = 'https://embed.tawk.to/60159e84c31c9117cb7429af/1eta8u6eh'
          @else
            s1.src = 'https://embed.tawk.to/60159e84c31c9117cb7429af/1eta9oe5o'
          @endif
            s1.charset = 'UTF-8'
        s1.setAttribute('crossorigin', '*')
        s0.parentNode.insertBefore(s1, s0)
      })()
    </script> -->
@endsection
