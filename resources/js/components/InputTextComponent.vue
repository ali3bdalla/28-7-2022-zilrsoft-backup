<template>
	<div class="field">
		<div class="control">
			<input :value="value" type='text' class="input" :placeholder="placeholder" @focus="applyAttrubite" :class="{'rtl':mode=='ar','ltr':mode=='en','is-danger':error}" :name="name" @keyup="startTyping" :readonly="isReadonly">
		</div>
		<p v-show="error" class="help is-danger is-center" v-text="errorMessage"></p>
	</div>
</template>

<script type="text/javascript">
	export default {
		props:['name','mode','placeholder',"hasError",'errorMessage','value','isReadonly'],
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
			applyAttrubite(){
				if(this.mode=='en'){

				}else{

				}
			},
			startTyping(){
				this.error = false;
			}
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