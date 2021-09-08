<v-toolbar color="primary" dark app
    :clipped-left="$vuetify.breakpoint.mdAndUp">
    @auth
        <v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>
    @endauth
    <a href="/dashboard" class="d-flex router-link-active">
        <img src="{{ asset('assets/images/white-logo.png') }}" height="40px">
    </a>
    {{-- <v-toolbar-title>{{ config('app.name') }}</v-toolbar-title> --}}
    <v-spacer></v-spacer>
    <v-toolbar-items class="hidden-sm-and-down">
        @auth
            {{-- <new-orders-notifications
                :notifications='{{ json_encode(Auth::user()->ordersNotifications()) }}'>
            </new-orders-notifications> --}}
        @endauth

        @auth
            <v-menu offset-y transition="slide-y-transition">
                <v-btn flat slot="activator">
                    <v-icon>account_circle</v-icon>
                </v-btn>
                <v-list>
                    <v-list-tile ripple href="#"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <v-list-tile-action>
                            <v-icon>power_settings_new</v-icon>
                        </v-list-tile-action>
                        <v-list-tile-content>
                            <v-list-tile-title>
                                {{ trans('dashboard.common.logout') }}
                            </v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>
                    <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </v-list>
            </v-menu>  
        @endauth

    </v-toolbar-items>
</v-toolbar>