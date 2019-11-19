<template>
    <form action="" method="POST">

        <div class="card">
            <div class="card-header"></div>
            <div class="card-body">


                <div class="form-group row">
                    <!--   <label for="email" class="col-md-4 col-form-label text-md-right">{{ trans('users.email_address') }}</label> -->
                    <div class="col-md-6">
                        <input autocomplete="name" autofocus class="form-control" id="name" name="name" placeholder="" required
                               type="name" value="">


                    </div>
                    <div class="col-md-6">
                        <input autofocus class="form-control is-invalid" id="phone_number" name="phone_number"
                               placeholder="" required type="text" value="">


                    </div>
                </div>


                <div class="form-group row mb-0" id='user_only_submit_btn'>
                    <div class="col-md-3">
                        <button class="btn btn-primary btn-block" type="submit">

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