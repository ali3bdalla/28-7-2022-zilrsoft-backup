<template>
	<div class="">
		<div class="field">
			<input  type='text' class="input" placeholder="s">
		</div>
		<div class="select is-fullwidth is-multiple">

			<div class="columns">
				<div class="column">

					<div class="controls">
						<select multiple="" v-on:change="addFilterToCategoryFilters">
							<option v-for="filter in unselected_list" :key="filter.id">{{ filter.locale_name }}</option>
						</select>
					</div>
				</div>
				<div class="column">
					<div class="controls">
						<select multiple="" v-on:change="removeFilterFromCategoryFilters">
							<option v-for="filter in selected_list" :value="filter.id" :key="filter.id">{{ filter.locale_name }}</option>
						</select>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>


<script>
	export default {
		props:['unusedList','usedList'],
		data:function(){
			return {
				selected_list:[],
				unselected_list:[],
			};
		},
		created:function(){
			this.selected_list = this.usedList;
			this.unselected_list = this.unusedList;
		},
		methods:{

			removeFilterFromCategoryFilters(e){


				// get the item 

				// removed form the selected list


				// add it to the unselected list
				console.log(e.target.value);
			},


			addFilterToCategoryFilters(e){

			}
		}
	}
</script>