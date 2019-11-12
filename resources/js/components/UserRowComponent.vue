<template>
        <tr>
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
            <td>
            <button class="btn btn-default btn-xs m-r-5" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil font-14"></i></button>
            <button class="btn btn-default btn-xs" data-toggle="tooltip" data-original-title="Delete"><i class="fa fa-trash font-14"></i></button>
            </td>
        </tr>
    
</template>








<script>
    export default {
        data:function(){
          return {
           
          };
        },
        props: ['user'],
        
    }
</script>

<style scoped>
   
</style>