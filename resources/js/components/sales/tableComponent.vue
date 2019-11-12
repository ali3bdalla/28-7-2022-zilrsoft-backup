<template>

    <div>

        <div class="table-container">
            <table class="table table-bordered text-center">
                <thead>
                <tr>
                    <th class="text-center" width="1%">#</th>
                    <th class="text-center">رقم الفاتورة</th>
                    <th class="text-center">العميل</th>
                    <th class="text-center">البائع</th>
                    <th class="text-center ">تاريخ الفاتورة</th>
                    <th class="text-center">النهائي</th>
                    <th class="text-center"> الحالة</th>
                    <th class="text-center">مسددة</th>
                    <th class="text-center">نوع الفاتورة</th>
                    <th class="text-center">المستخدم</th>
                    <th class="text-center">خيارات</th>
                </tr>
                </thead>
                <tbody>

                <tr v-for="(sale,index) in sales">
                    <td>{{index+1}}</td>
                    <td>{{sale.id}}</td>
                    <td>{{sale.client.name}}</td>
                    <td>{{sale.invoice.creator.name}}</td>
                    <td class="datedirection">{{sale.created_at}}</td>
                    <td>{{sale.invoice.net}}</td>
                    <td>
                        <span v-if="sale.invoice.issued_status=='paid'">{{ translator.paid}}</span>
                        <span v-else></span>
                    </td>
                    <td>نعم</td>
                    <td>مبيعات</td>
                    <td>ali</td>
                    <td width="8%">
                        <div class="dropdown">
                            <button aria-expanded="false" aria-haspopup="true" class="button is-primary"
                                    data-toggle="dropdown"
                                    id="optionsMenu1" type="button">
                                <svg aria-hidden="true" class="svg-inline--fa fa-cogs fa-w-20" data-fa-i2svg=""
                                     data-icon="cogs" data-prefix="fa" role="img"
                                     viewBox="0 0 640 512" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M512.1 191l-8.2 14.3c-3 5.3-9.4 7.5-15.1 5.4-11.8-4.4-22.6-10.7-32.1-18.6-4.6-3.8-5.8-10.5-2.8-15.7l8.2-14.3c-6.9-8-12.3-17.3-15.9-27.4h-16.5c-6 0-11.2-4.3-12.2-10.3-2-12-2.1-24.6 0-37.1 1-6 6.2-10.4 12.2-10.4h16.5c3.6-10.1 9-19.4 15.9-27.4l-8.2-14.3c-3-5.2-1.9-11.9 2.8-15.7 9.5-7.9 20.4-14.2 32.1-18.6 5.7-2.1 12.1.1 15.1 5.4l8.2 14.3c10.5-1.9 21.2-1.9 31.7 0L552 6.3c3-5.3 9.4-7.5 15.1-5.4 11.8 4.4 22.6 10.7 32.1 18.6 4.6 3.8 5.8 10.5 2.8 15.7l-8.2 14.3c6.9 8 12.3 17.3 15.9 27.4h16.5c6 0 11.2 4.3 12.2 10.3 2 12 2.1 24.6 0 37.1-1 6-6.2 10.4-12.2 10.4h-16.5c-3.6 10.1-9 19.4-15.9 27.4l8.2 14.3c3 5.2 1.9 11.9-2.8 15.7-9.5 7.9-20.4 14.2-32.1 18.6-5.7 2.1-12.1-.1-15.1-5.4l-8.2-14.3c-10.4 1.9-21.2 1.9-31.7 0zm-10.5-58.8c38.5 29.6 82.4-14.3 52.8-52.8-38.5-29.7-82.4 14.3-52.8 52.8zM386.3 286.1l33.7 16.8c10.1 5.8 14.5 18.1 10.5 29.1-8.9 24.2-26.4 46.4-42.6 65.8-7.4 8.9-20.2 11.1-30.3 5.3l-29.1-16.8c-16 13.7-34.6 24.6-54.9 31.7v33.6c0 11.6-8.3 21.6-19.7 23.6-24.6 4.2-50.4 4.4-75.9 0-11.5-2-20-11.9-20-23.6V418c-20.3-7.2-38.9-18-54.9-31.7L74 403c-10 5.8-22.9 3.6-30.3-5.3-16.2-19.4-33.3-41.6-42.2-65.7-4-10.9.4-23.2 10.5-29.1l33.3-16.8c-3.9-20.9-3.9-42.4 0-63.4L12 205.8c-10.1-5.8-14.6-18.1-10.5-29 8.9-24.2 26-46.4 42.2-65.8 7.4-8.9 20.2-11.1 30.3-5.3l29.1 16.8c16-13.7 34.6-24.6 54.9-31.7V57.1c0-11.5 8.2-21.5 19.6-23.5 24.6-4.2 50.5-4.4 76-.1 11.5 2 20 11.9 20 23.6v33.6c20.3 7.2 38.9 18 54.9 31.7l29.1-16.8c10-5.8 22.9-3.6 30.3 5.3 16.2 19.4 33.2 41.6 42.1 65.8 4 10.9.1 23.2-10 29.1l-33.7 16.8c3.9 21 3.9 42.5 0 63.5zm-117.6 21.1c59.2-77-28.7-164.9-105.7-105.7-59.2 77 28.7 164.9 105.7 105.7zm243.4 182.7l-8.2 14.3c-3 5.3-9.4 7.5-15.1 5.4-11.8-4.4-22.6-10.7-32.1-18.6-4.6-3.8-5.8-10.5-2.8-15.7l8.2-14.3c-6.9-8-12.3-17.3-15.9-27.4h-16.5c-6 0-11.2-4.3-12.2-10.3-2-12-2.1-24.6 0-37.1 1-6 6.2-10.4 12.2-10.4h16.5c3.6-10.1 9-19.4 15.9-27.4l-8.2-14.3c-3-5.2-1.9-11.9 2.8-15.7 9.5-7.9 20.4-14.2 32.1-18.6 5.7-2.1 12.1.1 15.1 5.4l8.2 14.3c10.5-1.9 21.2-1.9 31.7 0l8.2-14.3c3-5.3 9.4-7.5 15.1-5.4 11.8 4.4 22.6 10.7 32.1 18.6 4.6 3.8 5.8 10.5 2.8 15.7l-8.2 14.3c6.9 8 12.3 17.3 15.9 27.4h16.5c6 0 11.2 4.3 12.2 10.3 2 12 2.1 24.6 0 37.1-1 6-6.2 10.4-12.2 10.4h-16.5c-3.6 10.1-9 19.4-15.9 27.4l8.2 14.3c3 5.2 1.9 11.9-2.8 15.7-9.5 7.9-20.4 14.2-32.1 18.6-5.7 2.1-12.1-.1-15.1-5.4l-8.2-14.3c-10.4 1.9-21.2 1.9-31.7 0zM501.6 431c38.5 29.6 82.4-14.3 52.8-52.8-38.5-29.6-82.4 14.3-52.8 52.8z"
                                          fill="currentColor"></path>
                                </svg><!-- <i class="fa fa-cogs"></i> --> &nbsp; خيارات
                            </button>
                            <div aria-labelledby="optionsMenu1" class="dropdown-menu"><a
                                    class="dropdown-item" href="http://localhost/management/sales/1">
                                <svg aria-hidden="true" class="svg-inline--fa fa-eye fa-w-18" data-fa-i2svg=""
                                     data-icon="eye" data-prefix="fa" role="img" viewBox="0 0 576 512"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path d="M569.354 231.631C512.969 135.949 407.81 72 288 72 168.14 72 63.004 135.994 6.646 231.631a47.999 47.999 0 0 0 0 48.739C63.031 376.051 168.19 440 288 440c119.86 0 224.996-63.994 281.354-159.631a47.997 47.997 0 0 0 0-48.738zM288 392c-75.162 0-136-60.827-136-136 0-75.162 60.826-136 136-136 75.162 0 136 60.826 136 136 0 75.162-60.826 136-136 136zm104-136c0 57.438-46.562 104-104 104s-104-46.562-104-104c0-17.708 4.431-34.379 12.236-48.973l-.001.032c0 23.651 19.173 42.823 42.824 42.823s42.824-19.173 42.824-42.823c0-23.651-19.173-42.824-42.824-42.824l-.032.001C253.621 156.431 270.292 152 288 152c57.438 0 104 46.562 104 104z"
                                          fill="currentColor"></path>
                                </svg><!-- <i class="fa fa-eye"></i> --> &nbsp; عرض التفاصيل</a> <a
                                    class="dropdown-item" href="http://localhost/management/sales/1/edit">
                                <svg aria-hidden="true" class="svg-inline--fa fa-redo-alt fa-w-16" data-fa-i2svg=""
                                     data-icon="redo-alt" data-prefix="fa" role="img"
                                     viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M256.455 8c66.269.119 126.437 26.233 170.859 68.685l35.715-35.715C478.149 25.851 504 36.559 504 57.941V192c0 13.255-10.745 24-24 24H345.941c-21.382 0-32.09-25.851-16.971-40.971l41.75-41.75c-30.864-28.899-70.801-44.907-113.23-45.273-92.398-.798-170.283 73.977-169.484 169.442C88.764 348.009 162.184 424 256 424c41.127 0 79.997-14.678 110.629-41.556 4.743-4.161 11.906-3.908 16.368.553l39.662 39.662c4.872 4.872 4.631 12.815-.482 17.433C378.202 479.813 319.926 504 256 504 119.034 504 8.001 392.967 8 256.002 7.999 119.193 119.646 7.755 256.455 8z"
                                          fill="currentColor"></path>
                                </svg><!-- <i class="fa
							     fa-redo-alt"></i> --> &nbsp; مرتجع</a> <a class="dropdown-item" href="#">
                                <svg aria-hidden="true" class="svg-inline--fa fa-trash fa-w-14" data-fa-i2svg=""
                                     data-icon="trash" data-prefix="fa" role="img"
                                     viewBox="0 0 448 512" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M0 84V56c0-13.3 10.7-24 24-24h112l9.4-18.7c4-8.2 12.3-13.3 21.4-13.3h114.3c9.1 0 17.4 5.1 21.5 13.3L312 32h112c13.3 0 24 10.7 24 24v28c0 6.6-5.4 12-12 12H12C5.4 96 0 90.6 0 84zm415.2 56.7L394.8 467c-1.6 25.3-22.6 45-47.9 45H101.1c-25.3 0-46.3-19.7-47.9-45L32.8 140.7c-.4-6.9 5.1-12.7 12-12.7h358.5c6.8 0 12.3 5.8 11.9 12.7z"
                                          fill="currentColor"></path>
                                </svg><!-- <i class="fa fa-trash"></i> --> &nbsp; حذف</a> <a class="dropdown-item"
                                                                                             href="#">
                                <svg aria-hidden="true" class="svg-inline--fa fa-copy fa-w-14" data-fa-i2svg=""
                                     data-icon="copy" data-prefix="fa" role="img"
                                     viewBox="0 0 448 512" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M320 448v40c0 13.255-10.745 24-24 24H24c-13.255 0-24-10.745-24-24V120c0-13.255 10.745-24 24-24h72v296c0 30.879 25.121 56 56 56h168zm0-344V0H152c-13.255 0-24 10.745-24 24v368c0 13.255 10.745 24 24 24h272c13.255 0 24-10.745 24-24V128H344c-13.2 0-24-10.8-24-24zm120.971-31.029L375.029 7.029A24 24 0 0 0 358.059 0H352v96h96v-6.059a24 24 0 0 0-7.029-16.97z"
                                          fill="currentColor"></path>
                                </svg><!-- <i class="fa fa-copy"></i> --> &nbsp; نسخ</a></div>
                        </div>
                    </td>
                </tr>

                </tbody>
            </table>
        </div>


    </div>


</template>

<script>
    export default {

        data:function(){
            return {
                messages:null,
                translator:null,
                reusable_translator:null,
                sales:[]
            };
        },
        created: function () {
            this.loadData();

            this.translator = JSON.parse(window.translator);
            this.reusable_translator = JSON.parse(window.reusable_translator);
            this.messages = JSON.parse(window.messages);
        },
        methods: {
            loadData() {
                var params = [];
                var vm = this;
                axios.post('/management/sales/table/fetch', params).then(response => {
                    console.log(response.data);
                    vm.sales = response.data.data;
                })
            }
        }
    }
</script>