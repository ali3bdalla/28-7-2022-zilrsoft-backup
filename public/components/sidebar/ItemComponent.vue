<template>
	<li>
		<a :href="url">
	        <i  :class="icon"></i> <span v-text="title"></span>
	       <!--  <span class="pull-right-container" v-show="hasBg">
	      		<small class="label pull-right bg-green">Hot</small>
	    	</span> -->
	    </a>
	</li>
</template>

<script>
	export default {
		props:['url','title','icon']
	}
</script>

<style scoped>
	li {
		/*border-bottom:1px solid #000;*/
		border-top:1px solid #000;
	}

	span {
		margin-left:10px !important
	}
</style>