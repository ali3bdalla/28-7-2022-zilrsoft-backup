<template>
	<form method="POST" action="">

        <div class="card">
            <div class="card-header"></div>
            <div class="card-body">
                

                <div class="form-group row">
                  <!--   <label for="email" class="col-md-4 col-form-label text-md-right">{{ trans('users.email_address') }}</label> -->
					<div class="col-md-6">
                        <input id="name" type="name" class="form-control" name="name" value="" placeholder="" required autocomplete="name" autofocus>

                        
                            
                     
                    </div>
                    <div class="col-md-6">
                        <input id="phone_number" type="text" class="form-control is-invalid" name="phone_number" value="" required  placeholder="" autofocus>

                      
                    </div>
                </div>


                <div class="form-group row mb-0" id='user_only_submit_btn'>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary btn-block">
                           
                        </button>
                    </div>
                </div> 
            </div>
        </div>
        <hr/>
    </form>
</template>



<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>