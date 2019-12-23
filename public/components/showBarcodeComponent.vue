<template>
    <div style="background:red">
        hello
    </div>
</template>
<script>
    export  default  {
        props:['title'],
        created:function () {
            console.log(this.title);
        }

    }
</script>