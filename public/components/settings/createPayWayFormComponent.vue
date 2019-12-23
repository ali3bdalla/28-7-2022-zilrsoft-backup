<template>
    <div>
        <div class="columns">
            <div class="column">
                <div class="form-group">
                    <input class="form-control" name="name" placeholder="method name" type="text"
                           value=""/>
                </div>

            </div>
            <div class="column">
                <div class="form-group">
                    <input class="form-control" name="ar_name" placeholder="method name in arabic" type="text"
                           value=""/>
                </div>

            </div>
        </div>

        <div class="form-group">
            <div class="">
                <input class="input" disabled type="text" value=""/>
                <input class="input" name="parent_id" type="hidden" value=""/>
            </div>
        </div>
        <input class="input" name="parent_id" type="hidden" value="0"/>
        <div class="form-group">
            <div class="columns text-center">

                <div class="column">
                    <toggle-button :font-size="19" :height='30' :sync="true" :width='70' labels=""/>
                     <label> يمكن انشاء وسائل فرعية منها</label>
                </div>
                <div class="column">

                    <toggle-button :font-size="19" :height='30' :sync="true" :width='70' labels=""/> <label>هل تحتاج رقم واحد (شيك
                    مثلا)</label>
                </div>

                <div class="column"> <toggle-button :font-size="19" :height='30' :sync="true" :width='70' labels=""/> <label>تحتاج حساب من الحسابين</label>
                </div>


            </div>
        </div>
        <button class="button is-primary" type="submit">create method</button>
    </div>

</template>