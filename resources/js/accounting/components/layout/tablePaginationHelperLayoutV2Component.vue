<template>
    <div class="">
        <div class="row" v-if="more!=true">
            <div class="col-md-2">
                <div class="pager" v-show="viewOnly!=true && more!=true">
                    <select @change="updateItemsPerPageValue" class="form-control" v-model="itemsPerPage">
                        <option value="20">عدد العناصر في الصفحة (20)</option>
                        <option value="30">30</option>
                        <option value="50">50</option>
                        <option value="80">80</option>
                        <option value="100">100</option>
                      <option value="250">250</option>
                      <option value="300">300</option>
                    </select>
                    <!--                    <input @keyup="updateItemsPerPageValue" class="form-control" placeholder="عدد العناصر في الصفحة"-->
                    <!--                           type="text"-->
                    <!--                           v-model="itemsPerPage">-->
                </div>
            </div>
            <div class="col-md-10">
                <nav >
                    <ul class="pager">
                        <li class="previous" v-if="current_page!=1" v-show="viewOnly!=true"> <a
                                @click="pageSelected(first_page_url)" v-html="pagination_trans.first"></a></li>
                        <li v-if="prev_page_url!=null && prev_page_url!=current_page_url" v-show="viewOnly!=true"><a
                                @click="pageSelected(prev_page_url)" v-html="pagination_trans.previous"></a></li>
                        <li v-for="n in shownPages"><a
                                :class="{'bg-custom-primary':current_page==n}"
                                @click="pageSelected(path+'?page=' + (n))"
                                class="active">{{
                            (n)}}</a></li>

                        <li v-if="next_page_url!=null &&  next_page_url!=current_page_url" v-show="viewOnly!=true"><a
                                @click="pageSelected(next_page_url)" v-html="pagination_trans.next"></a></li>
                        <li class="next" v-if="current_page!=last_page" v-show="viewOnly!=true"><a
                                @click="pageSelected(last_page_url)"
                                                                           v-html="pagination_trans.last"></a>
                        </li>
                    </ul>
                </nav>

            </div>
        </div>

        <div class="text-center" v-else>
            <button v-if="next_page_url!=null &&  next_page_url!=current_page_url" @click="pageSelected(next_page_url)" class="btn btn-primary">المزيد</button>
        </div>

    </div>
</template>

<script>
    export default {
        props: ["data",'viewOnly','more'],
        data: function () {
            return {
                itemsPerPage: 20,
                pagination_trans: trans('pagination'),
                shownPages: [],
                paginationData: null,
                first_page_url: "",
                from: 0,
                last_page: 0,
                last_page_url: "",
                next_page_url: "",
                path: "",
                per_page: "",
                prev_page_url: null,
                to: "",
                total: "",
                current_page: "",
                current_page_url: "",
            };
        },
        created() {
            this.paginationData = this.data;
            this.updatePaginationData();
        },
        methods: {
            updatePaginationData() {
                if (this.paginationData != null) {
                    this.total = this.paginationData.meta.total;
                    this.current_page = this.paginationData.meta.current_page;
                    this.current_page_url = this.path + '?page=' + this.current_page;
                    this.to = this.paginationData.meta.to;
                    this.path = this.paginationData.meta.path;
                    this.prev_page_url = this.paginationData.links.prev;
                    // this.total = this.paginationData.meta.total;
                    this.first_page_url = this.paginationData.links.first;
                    this.next_page_url = this.paginationData.links.next;
                    this.last_page_url = this.paginationData.links.last;
                    this.last_page = this.paginationData.meta.last_page;
                    this.updateShownPages();
                }

            },
            updateShownPages() {
                var pages = this.last_page - this.current_page;


                this.shownPages = [];
                if (this.prev_page_url != null) {
                    var counter = 1;
                    for (var i = 0; i < this.current_page - 1 && counter <= 5; i++ && counter++) {
                        this.shownPages.push(i + 1);
                    }
                }

                if (pages > 5) {
                    var end = this.current_page + 5;

                    for (var i = this.current_page; i <= end; i++) {
                        this.shownPages.push(i);
                    }
                } else {
                    for (var i = this.current_page; i <= this.last_page; i++) {
                        this.shownPages.push(i);
                    }
                }


            },
            pageSelected(link) {
                this.$emit('paginateUpdatePage', {
                    link: link
                });
            },
            updateItemsPerPageValue(e) {

                this.itemsPerPage = e.target.value;

                if (parseInt(this.itemsPerPage)) {
                    this.$emit('pagePerItemsUpdated', {
                        items: this.itemsPerPage
                    });
                }
            }
        },
        watch: {
            data: function (value) {
                this.paginationData = value;
                this.updatePaginationData();
            }
        }
    }
</script>
<style>
    li {
        cursor: pointer;
    }
</style>