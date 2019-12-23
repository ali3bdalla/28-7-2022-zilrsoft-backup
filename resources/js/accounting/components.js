// import AccountingLoginViewComponent from './components/auth/loginView';
// layouts
import AccountingTableFiltersSearchComponent from './components/layout/tableFiltersSearch';
import AccountingTreeViewRawLayoutComponent from './components/layout/treeViewRawLayoutComponent';
import AccountingHeaderLayoutComponent from './components/layout/headerLayoutComponent';
import accountingAppendGatewaysLayoutComponent from './components/layout/appendGatewaysLayoutComponent';
import AccountingTablePaginationHelperLayoutComponent from './components/layout/tablePaginationHelperLayoutComponent';
import AccountingSelectWithSearchComponent from './components/layout/selectWithSearchComponent';
import AccountingMultiSelectWithSearchComponent from './components/layout/multiSelectWithSearchLayoutComponent';
import accountingFilterSelectWithSearchComponent from './components/layout/filterSelectWithSearchComponent';
import AccountingBranchesDatatableComponent from './components/branches/branchesDatatableComponent';
import AccountingDepartmentsDatatableComponent from './components/branches/departmentsDatatableComponent';
// items
import AccountingItemsDatatableComponent from './components/items/itemsDatatableComponent';
import AccountingItemsCreateComponent from './components/items/itemsCreateComponent';
//categories
import accountingCategoryFiltersListComponent from './components/categories/categoryFiltersListComponent';
// filters
import AccountingFiltersDatatableComponent from './components/filters/filtersDatatableComponent';
import AccountingEditFilterAndValuesComponent from './components/filters/editFilterAndValuesComponent';
// users
import AccountingIdentitiesDatatableComponent from './components/users/identitiesDatatableComponent';
import AccountingManagersDatatableComponent from './components/users/managersDatatableComponent';
import AccountingIdentitiesCreateComponent from './components/users/identitiesCreateComponent';
import AccountingManagersCreateComponent from './components/users/managersCreateComponent';
import AccountingManagerPermissionAndRoleComponent from './components/users/managerPermissionAndRoleComponent';
// dashboards
import AccountingDashboardItemsChartComponent from './components/dashboard/dashboardItemsChartComponent';

// Vue.component('accounting-login-view-component', AccountingLoginViewComponent);

Vue.component('accounting-table-filter-search-component', AccountingTableFiltersSearchComponent);

Vue.component('accounting-header-layout-component', AccountingHeaderLayoutComponent);
Vue.component('accounting-append-gateways-layout-component', accountingAppendGatewaysLayoutComponent);
Vue.component('accounting-treeview-raw-layout-component', AccountingTreeViewRawLayoutComponent);

Vue.component('accounting-table-pagination-helper-layout-component', AccountingTablePaginationHelperLayoutComponent);

Vue.component('accounting-select-with-search-layout-component', AccountingSelectWithSearchComponent);
Vue.component('accounting-multi-select-with-search-layout-component', AccountingMultiSelectWithSearchComponent);
Vue.component('accounting-filter-select-with-search-component', accountingFilterSelectWithSearchComponent);

Vue.component('accounting-items-datatable-component', AccountingItemsDatatableComponent);

Vue.component('accounting-items-create-component', AccountingItemsCreateComponent);


Vue.component('accounting-category-filters-list-component', accountingCategoryFiltersListComponent);

Vue.component('accounting-filters-datatable-component', AccountingFiltersDatatableComponent);
Vue.component('accounting-edit-filter-and-values-component', AccountingEditFilterAndValuesComponent);

Vue.component('accounting-identities-datatable-component', AccountingIdentitiesDatatableComponent);
Vue.component('accounting-managers-datatable-component', AccountingManagersDatatableComponent);
Vue.component('accounting-identities-create-component', AccountingIdentitiesCreateComponent);
Vue.component('accounting-managers-create-component', AccountingManagersCreateComponent);
Vue.component('accounting-managers-permissions-and-roles-component', AccountingManagerPermissionAndRoleComponent);


Vue.component('accounting-dashboard-items-chart-component', AccountingDashboardItemsChartComponent);
Vue.component('accounting-branches-datatable-component', AccountingBranchesDatatableComponent);
Vue.component('accounting-departments-datatable-component', AccountingDepartmentsDatatableComponent);