import Vue from  'vue';
import gridItemsCollectionComponent from "./components/items/gridItemsCollectionComponent";
import cellItemComponent from "./components/items/cellItemComponent";
import searchFiltersComponent from "./components/filters/searchFiltersComponent";



Vue.component('grid-items-collection-component',gridItemsCollectionComponent);
Vue.component('cell-item-component',cellItemComponent);
Vue.component('search-filters-component',searchFiltersComponent);