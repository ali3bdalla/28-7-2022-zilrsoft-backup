<template>
	<div class="field">
		<div class="control">
			<textarea type='text' 
			class="textarea" :placeholder="placeholder" :class="{'rtl':mode=='ar','ltr':mode=='en','is-danger':error}" :name="name" >{{value}}</textarea>
		</div>
		<p v-show="error" class="help is-danger is-center" v-text="errorMessage"></p>
	</div>
</template>

<script type="text/javascript">
	export default {
		props:['name','mode','placeholder','hasError','errorMessage','value'],
		created:function(){
			this.initMode();
			this.setAttrubites();
		},
		data:function(){
			return {
				error:false
			};
		},
		methods:{
			initMode(){
			},
			setAttrubites(){
				this.error = this.hasError;
			},
			// applyAttrubite(){
			// 	if(this.mode=='en'){
			//
			// 	}else{
			//
			// 	}
			// },
			// startTyping(){
			// 	this.error = false;
			// }
		}

	}
</script>


<style scoped>
.rtl {
	direction: rtl;
}

.ltr {
	direction: ltr;
}
</style>