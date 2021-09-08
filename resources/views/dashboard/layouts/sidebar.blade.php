<v-navigation-drawer
  v-model="drawer"
  absolute
  app
  :right="appLocal == 'ar'"
  :clipped="$vuetify.breakpoint.mdAndUp"
  :mini-variant="$vuetify.breakpoint.xsOnly">
  <v-list class="pa-1 sidebar-profile-container">
    {{-- <v-list-tile v-if="mini" @click.stop="mini = !mini">
      <v-list-tile-action>
        <v-icon>chevron_right</v-icon>
      </v-list-tile-action>
    </v-list-tile> --}}

    <v-list-tile avatar tag="div">
      <v-list-tile-avatar>
        <img class="datatable-avatar" style="width:50px;height:50px" src="{{ Auth::user()->avatar }}">
      </v-list-tile-avatar>

      <v-list-tile-content>
        <v-list-tile-title>{{ Auth::user()->name }}</v-list-tile-title>
      </v-list-tile-content>

      {{-- <v-list-tile-action>
        <v-btn icon @click.stop="mini = !mini">
          <v-icon>chevron_left</v-icon>
        </v-btn>
      </v-list-tile-action> --}}
    </v-list-tile>
  </v-list>

  <v-list class="pt-0" dense>
    <v-divider light></v-divider>
    @include('dashboard.layouts.menu')
  </v-list>
</v-navigation-drawer>
            