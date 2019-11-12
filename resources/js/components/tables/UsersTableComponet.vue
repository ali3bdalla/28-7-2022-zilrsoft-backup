<template>
    <table class="table">
        <thead>
        <tr>
            <th width="50px"></th>
            <th>{{ window.translator_users.name }}</th>
            <th>Price</th>
            <th>Data</th>
            <th>Last Name</th>
            <th>created at</th>

            <th>manage</th>
        </tr>
        </thead>
        <tbody>
            <tr v-for="user in users">
                <td>
                    <label class="ui-checkbox">
                        <input type="checkbox">
                        <span class="input-span"></span>
                    </label>
                </td>
                <td>{{ user.name }}</td>
                <td>$3280</td>
                <td>42%</td>

                <td>08/10/2017</td>

                <td>{{ user.created_at }}</td>
                <td>
                    <button class="btn btn-default btn-xs m-r-5" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil font-14"></i></button>
                    <button class="btn btn-default btn-xs" data-toggle="tooltip" data-original-title="Delete"><i class="fa fa-trash font-14"></i></button>
                </td>
            </tr>
        </tbody>

    </table>
</template>

<script>
    export default {
        data:function(){
            return {

            };
        },
        props: ['users'],

    }
</script>

<style scoped>

</style>
