<template>
  <div class="product__search-filters" v-click-outside="hide">
    <button
      @click="show"
      class="product__search-option-button"
      style="background: rgb(87, 87, 87)"
    >
      <svg
        xmlns="http://www.w3.org/2000/svg"
        class="product__search-option-icon"
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
      <!-- {{ $page.$t.products.filters }} -->
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
        <!-- <div class="closeBtnClass">
          <i @click="quiteModel" class="fa fa-close"></i>
          {{ $page.$t.products.filters_for_search }} {{ searchName }} ({{
            selectedValues.length
          }})
        </div> -->
        <div class="container  mb-2">
           <div class="row page__mt-5">
            <div class="col-md-6 col-6  text-center">
              <button
                @click="hide"
                class="btn btn-primary applyBtn px-5"
              >
                {{ $page.$t.products.apply }}
              </button>
            </div>
            <div class="col-md-6 col-6 text-center">
              <button
                @click="hide"
                class="btn btn-default resetBtn bg-web-primary px-5"
              >
                {{ $page.$t.products.reset }}
              </button>
            </div>
          </div>
        </div>

        <div class="container-fluid filters-layout-modal">
          <div class="row">
            <div
              class="col-md-6 border"
              v-for="(filter, index) in filters"
              :key="filter.id"
            >
                          <!-- :class="[((index+1) % 2) == 0 ? 'toGrayBg' : 'bg-white']" -->

              <div class="filter-widget">
                <h6 class="fw-title">
                  {{ filter.filter.locale_name }}
                </h6>

                <div class="fw-brand-check">
                  <div class="row">
                    <div
                      class="col-md-6 col-6"
                      v-for="val in filter.values"
                      :key="val.id"
                    >
                      <div
                        class="product__search-filter-value"
                        style="font-size: 15px; color: #575555"
                      >
                        <!-- <label :for="'value_' + val.id"> -->
                        <input
                          type="checkbox"
                          :id="'value_' + val.id"
                          :checked="selectedValues.includes(val.id)"
                          @change="toggleItemFilterValue(val.id)"
                        />
                        <!-- <span class="checkmark"></span> -->

                        {{ val.locale_name }}
                        <!-- </label> -->
                      </div>
                    </div>
                  </div>
                </div>
              </div>
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
  methods: {
    hide () {
      this.$modal.hide('filtersLayoutModal')
    },

    show () {
      this.$modal.show('filtersLayoutModal')
    }
  }
}
</script>

<style >
.filter-widget {
  margin-bottom: 10px !important;
}
.vm--overlay {
  overflow-y: scroll;
}
.filters-layout-modal {
  padding-top: 5px;
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
  height: 42px;
  border-radius: 17px;
  box-shadow: 1px 5px 7px #c1baba;
}

.resetBtn {
  height: 42px;
  border-radius: 17px;
  box-shadow: 2px 1px 7px #c1baba;
}

.fw-title {
  /*color: #777;*/
  color: #252424;

  font-size: 13px !important;
  text-transform: lowercase;
  /* border-bottom: 1px solid #e8e0e0; */
  padding-bottom: 0px;
  margin-bottom: 5px;
  margin-top: 20px;
}
.fw-brand-check {
  padding-left: 20px;
}

.container-fluid .filters-layout-modal .filter-widget {
  margin-bottom: 10px !important;
}

.loading-progress {
  padding-top: 10%;
}

.closeBtnClass {
  padding: 17px;
  padding-bottom: 0px;
  font-size: 19px;
  font-weight: bold;
  text-align: right;
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

.toGrayBg {
  background: #f9f9f9;
}
</style>
