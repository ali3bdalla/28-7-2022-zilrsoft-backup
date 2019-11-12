<template>
	<div class="">
		<button @click="getSelectedRows()" class="button"></button>
   	<ag-grid-vue style="width: 100%; height: 1000px;"
                 class="ag-theme-balham"
                 :columnDefs="columnDefs"
                 rowSelection="multiple"
                 @grid-ready="onGridReady"
                 :rowData="rowData">
    </ag-grid-vue>
	</div>
</template>

<script>
    import {AgGridVue} from "ag-grid-vue";

    export default {
        name: 'App',
        data() {
            return {
                columnDefs: null,
                defaultColDef: {
			        resizable: true
			    },
                rowData: null,
                gridApi: null,
                columnApi: null,
                autoGroupColumnDef: null	
            }
        },
        components: {
            AgGridVue
        },
        methods: {
            onGridReady(params) {
                this.gridApi = params.api;
                this.columnApi = params.columnApi;
            },
            getSelectedRows() {
                const selectedNodes = this.gridApi.getSelectedNodes();
                const selectedData = selectedNodes.map( node => node.data );
                const selectedDataStringPresentation = selectedData.map( node => node.make + ' ' + node.model).join(', ');
                alert(`Selected nodes: ${selectedDataStringPresentation}`);
            }
        },
        beforeMount() {
        	
            this.columnDefs = [
                {headerName: 'id', field: 'id', checkboxSelection: true, suppressSizeToFit: true},
                {headerName: 'barcode', field: 'barcode',filter:true, suppressSizeToFit: true},
                {headerName: 'name', field: 'name',filter: "agTextColumnFilter",filterParams: {
			        clearButton: true,
			        applyButton: true,
			        debounceMs: 200
			    }, suppressSizeToFit: true},
                {headerName: 'name', field: 'ar_name',filter:"agSetColumnFilter", suppressSizeToFit: true},
                
                {headerName: 'created_at', field: 'created_at',filter:"agDateColumnFilter", suppressSizeToFit: true},
            ];


            fetch('/management/items/table/list')
	        .then(result => result.json())
	        .then(rowData => this.rowData = rowData);
            // this.rowData = [
            //     {make: 'Toyota', model: 'Celica', price: 35000},
            //     {make: 'Ford', model: 'Mondeo', price: 32000},
            //     {make: 'Porsche', model: 'Boxter', price: 72000}
            // ];
        }
    }
</script>
<style lang="scss">
// @import "../../../../node_modules/ag-grid-community/src/styles/ag-grid.scss";
//   @import "../../../../node_modules/ag-grid-community/src/styles/ag-theme-material/sass/ag-theme-material.scss";


  @import "../../../../node_modules/ag-grid-community/dist/styles/ag-grid.css";
  @import "../../../../node_modules/ag-grid-community/dist/styles/ag-theme-balham.css";
</style>

<style>
.table {
	border:1px solid 
}
</style>