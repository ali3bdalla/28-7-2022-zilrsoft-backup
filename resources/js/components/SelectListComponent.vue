<template>
	<div class="field">
		<div class="control">
			<div class="select is-fullwidth"   :class="{'is-danger':error}" >
			<select   :name="name" @change="startTyping">
				<option v-show="hasDefault"  :value="defaultValue">{{defaultCapture}}</option>
				<option v-for='item in items' :key="item.id" :value="item.id" :selected="item.id==selected">
					{{ item[captureText] }}
				</option>
			</select>
			</div>

		</div>
		<p v-show="error" class="help is-danger is-center" v-text="errorMessage"></p>
	</div>
</template>
<!-- <script src="/js/jquery.min.js"></script>
<script src="/js/jquery.searchableSelect.js"></script> -->
<script type="text/javascript">
	export default {
		props:['name','hasError','errorMessage','selected','items','captureText',
			'hasDefault',
			'defaultCapture',
			'defaultValue'
		],
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
