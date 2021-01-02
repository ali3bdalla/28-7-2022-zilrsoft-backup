<template>
  <div class="w-full md:w-48 text-white">
    <button
      @click="showFiltersLayout"
      class="w-full flex justify-center gap-3 items-center py-1"
      style="background: rgb(87, 87, 87)"
    >
      <svg
        xmlns="http://www.w3.org/2000/svg"
        class="w-4 h-4"
        fill="none"
        viewBox="0 0 24 24"
        stroke="currentColor"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
          d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"
        />
      </svg>
      فلاتر
    </button>

    <modal
      :scrollable="true"
      name="filtersLayoutModal"
      class="filtersLayoutModal"
      :adaptive="true"
      width="100%"
      height="100%"
      style="overflow-y: scroll"
    >
      <div style="overflow-y: scroll; height: 100vh !important">
        <div class="closeBtnClass">
          <i @click="quiteModel" class="fa fa-close"></i> Selected Filters ({{
            selectedValues.length
          }})
        </div>
        <div
          class="container-fluid filters-layout-modal loading-progress"
          v-if="isSendingApiRequest"
          width="100%"
          height="100%"
          style="overflow-y: scroll"
        >
          <circle-spin
            class="loading"
            v-show="isSendingApiRequest"
          ></circle-spin>
        </div>
        <div class="container-fluid filters-layout-modal" v-else>
          <div class="row">
            <div class="col-md-6" v-for="filter in filters" :key="filter.id">
              <div class="filter-widget">
                <h4 class="fw-title">
                  {{ filter.locale_name }}
                </h4>

                <div class="fw-brand-check">
                  <div class="row">
                    <div
                      class="col-md-6 col-6"
                      v-for="val in filter.values"
                      :key="val.id"
                    >
                      <div class="bc-item">
                        <label :for="'value_' + val.id">
                          <input
                            type="checkbox"
                            :id="'value_' + val.id"
                            :checked="selectedValues.includes(val.id)"
                            @change="toggleValueAvailability(val)"
                          />
                          <span class="checkmark"></span>

                          {{ val.locale_name }}
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 col-6">
              <button
                @click="applyFilters"
                class="btn btn-primary btn-block applyBtn"
              >
                Apply
              </button>
            </div>
            <div class="col-md-6 col-6">
              <button
                @click="resetFilters"
                class="btn btn-default btn-block resetBtn"
              >
                Reset
              </button>
            </div>
          </div>
          <div class="form-group"></div>
        </div>
      </div>
    </modal>
  </div>
</template>

<script>
export default {
  props: ["categoryId"],
  name: "ProductListItemComponentFilters",

  data: function () {
    return {
      isSendingApiRequest: false,
      filters: [],
      isSubCategoriesPanelOpen: false,
      selectedSubCategoryId: 0,
      subcategories: [],
      isFilterLayoutOpen: false,
      selectedFilters: [],
      selectedValues: [],
    };
  },
  created() {
    // this.getSubCategories();
    this.getApiFilters();
  },
  methods: {
    quiteModel() {
      this.resetFilters();
      this.closeModel();
    },
    applyFilters() {
      this.$emit("selectedAttributesHasBeenUpdated", {
        selectedValues: this.selectedValues,
      });
      this.closeModel();
    },
    resetFilters() {
      this.selectedSubCategoryId = 0;
      this.selectedValues = [];
      this.selectedFilters = [];
      this.getApiFilters();
    },
    closeModel() {
      this.$modal.hide("filtersLayoutModal");
    },
    toggleSubCategoriesPanel() {
      this.isSubCategoriesPanelOpen = !this.isSubCategoriesPanelOpen;
    },
    getSelectedCategory() {
      //  this.selectedSubCategoryId === 0 ?
      // : this.selectedSubCategoryId
      //   return  this.categoryId ;
      return 2;
      return this.$page.category_id;
    },

    getSelectedFiltersMap() {
      return this.selectedFilters;
    },

    getSelectedValues() {
      return this.selectedValues;
    },

    getApiFilters() {
      this.isSendingApiRequest = true;
      let appVm = this;
      console.log({
        filters: this.getSelectedFiltersMap(),
        values: this.getSelectedValues(),
        category_id: this.getSelectedCategory(),
      });
      axios
        .post(`/api/web/filters`, {
          filters: this.getSelectedFiltersMap(),
          values: this.getSelectedValues(),
          category_id: this.getSelectedCategory(),
        })
        .then(function (response) {
          appVm.handleGetFiltersResponse(response.data);
        })
        .catch(function (error) {
          alert(`server error : ${error}`);
        })
        .finally(function () {
          appVm.isSendingApiRequest = false;
        });
    },
    handleGetFiltersResponse(data = []) {
      let tempSelectedValuesArray = [];
      this.filters = data;
      for (let i = 0; i < data.length; i++) {
        let filter = data[i];
        for (let j = 0; j < filter.values.length; j++) {
          if (this.selectedValues.includes(filter.values[j].id))
            tempSelectedValuesArray.push(filter.values[j].id);
        }
      }

      this.selectedValues = tempSelectedValuesArray;
    },

    async toggleFilterChildrenLayoutAvailability(filter) {
      let filterId = filter.id;
      if (!this.selectedFilters.includes(filterId)) {
        await this.selectedFilters.push(filterId);
      } else {
        await this.selectedFilters.splice(
          this.selectedFilters.indexOf(filterId),
          1
        );
      }
    },

    async toggleValueAvailability(value) {
      let valueId = value.id;
      if (!this.selectedValues.includes(valueId)) {
        await this.selectedValues.push(valueId);
      } else {
        await this.selectedValues.splice(
          this.selectedFilters.indexOf(valueId),
          1
        );
      }

      this.getApiFilters();
    },

    showFiltersLayout() {
      this.$modal.show("filtersLayoutModal");
    },

    toggleSubCategory(subCategory) {
      if (subCategory.id === this.selectedSubCategoryId)
        this.selectedSubCategoryId = 0;
      else this.selectedSubCategoryId = subCategory.id;

      this.$emit("subCategoryHasBeenUpdated", {
        selectedSubCategoryId: this.selectedSubCategoryId,
      });
      this.getApiFilters();
    },
    getSubCategories() {
      let appVm = this;
      axios
        .get(getRequestUrl(`subcategories/${this.categoryId}`))
        .then(function (response) {
          appVm.subcategories = response.data;
        })
        .catch(function (error) {
          alert(`server error : ${error}`);
        })
        .finally(function () {});
    },
  },
};
</script>

<style scoped>
.vm--overlay {
  overflow-y: scroll;
}
.filters-layout-modal {
  padding-top: 20px;
}

.toggleButton {
  font-size: 20px;
  color: #888888;
  margin-right: 28px;
  position: relative;
  background: none;
  border: none;
  /*text-decoration: underline;*/
  display: flex;
  padding-top: 4px;
}

.applyBtn {
  height: 55px;
  border-radius: 17px;
  box-shadow: 1px 5px 7px #c1baba;
}

.resetBtn {
  height: 55px;
  border-radius: 17px;
  box-shadow: 2px 1px 7px #c1baba;
}

.fw-title {
  /*color: #777;*/
  color: #252424;

  font-size: 16px;
  text-transform: lowercase;
  border-bottom: 1px solid #e8e0e0;
  padding-bottom: 10px;
  margin-bottom: 10px;
  margin-top: 20px;
}
.fw-brand-check {
  padding-left: 20px;
}

.container-fluid .filters-layout-modal .filter-widget {
  margin-bottom: 10px;
}

.loading-progress {
  padding-top: 10%;
}

.closeBtnClass {
  padding: 17px;
  padding-bottom: 0px;
  font-size: 19px;
  font-weight: bold;
}

.closeBtnClass i {
  font-size: 22px;
  color: #777;
  margin: 6px;
  border: 1px solid #eee;
  padding: 8px;
  border-radius: 50%;
  box-shadow: 0px 2px 5px #ddd;
  cursor: pointer;
}
</style>
