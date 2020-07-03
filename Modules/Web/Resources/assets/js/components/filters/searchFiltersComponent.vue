<template>
    <div>
        <div class="filter-widget">
            <h4 class="fw-title">Price</h4>
            <div class="filter-range-wrap">
                <div class="range-slider">
                    <div class="price-input">
                        <input type="text" id="minamount">
                        <input type="text" id="maxamount">
                    </div>
                </div>
                <div class="price-range ui-slider ui-corner-all ui-slider-horizontal ui-widget ui-widget-content" data-min="33" data-max="98">
                    <div class="ui-slider-range ui-corner-all ui-widget-header"></div>
                    <span tabindex="0" class="ui-slider-handle ui-corner-all ui-state-default" style="left: 0%;"></span>
                    <span tabindex="0" class="ui-slider-handle ui-corner-all ui-state-default" style="left: 100%;"></span>
                    <div class="ui-slider-range ui-corner-all ui-widget-header" style="left: 0%; width: 100%;"></div></div>
            </div>
            <a href="#" class="filter-btn">Filter</a>
        </div>

        <div class="filter-widget" v-for="attr in attributes" v-if="isLoaded">
            <h4 class="fw-title">{{ attr.locale_name }}</h4>
            <div class="fw-tags">
                <a @click="toggleSelectedAttributes(val,attr)" class="hand-mouse"  v-for="val in attr.values" :class="{'bg-primary text-white':isInArray(JSON.stringify(getJsonObj(val,attr)))}" :key="val.id"> {{ val.locale_name }}</a>
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        props:['categoryId'],
        name: "searchFiltersComponent",
        data:function(){
          return {
              isLoaded:true,
              selectedAttributes:[],
              attributes:[]
          }
        },
        created() {
            this.getFilters();
        },
        methods:{

            getJsonObj(value,attr)
            {
                return  {
                    filter_id:attr.id,
                    value_id:value.id,
                }
            },
            isInArray(obj)
            {
                return window.inArray(obj,this.selectedAttributes);
            },
            getFilters()
            {
                let appVm = this;
                axios.get(getRequestUrl(`filters?category_id=${this.categoryId}`)).then(function(response){
                    appVm.attributes = response.data;

                }).catch(function(error) {
                    alert(`server error : ${error}`);
                }).finally(function () {
                });
            },

            toggleSelectedAttributes(value,attr) {
                let obj = this.getJsonObj(value,attr);
                this.isLoaded = false;


                if (this.isInArray(JSON.stringify(obj))){
                    let index = window.getIndex(JSON.stringify(obj),this.selectedAttributes);
                    this.selectedAttributes.splice(index,1);
                }else {
                    this.selectedAttributes.push(JSON.stringify(obj));
                }


                this.$emit('selectedAttributesHasBeenUpdated',{
                    selectedAttributes:this.selectedAttributes
                });

                this.isLoaded = true;
            }
        }
    }
</script>

<style scoped>

</style>