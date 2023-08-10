<template>
    <div class="container-fluid">
        <div class="card card-primary card-outline" id="newworkflow">
            <div class="card-header bg-white">
                <h3 class="card-title"><b>Password Reset</b></h3>
            </div>
            <div class="card-body">
                <div class="bootbox-body">
                    <form class="change-password-form">
                        <div class="">
                            <div class="row form-group">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <span class="text-danger" id="error_msg"></span>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <label>Email Address:<span class="text-danger">*</span></label>
                                    <input name= "email" type="text" id="email" @change="remove_error('email','email_err')"  class="form-control" v-model="form.email"/>
                                    <span class="text-danger" id="email_err"></span>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <label>New Password:<span class="text-danger">*</span></label>
                                    <input name="new_password" id="new_password" @change="remove_error('new_password','new_password_err')" class="form-control" v-model="form.new_password"  type="password">
                                    <span class="text-danger" id="new_password_err"></span>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <label>Confirm Password:<span class="text-danger">*</span></label>
                                    <input name="confirm_password" id="confirm_password" @change="remove_error('confirm_password','confirm_password_err')" class="form-control" v-model="form.confirm_password"  type="password">
                                    <span class="text-danger" id="confirm_password_err"></span>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-footer">
                <div class="row form-group fa-pull-right">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-flat btn-primary" @click="updatepassword()" :disabled="isLoading">
                            <i class="fa fa-edit"></i> Reset
                        </button>
                        <div v-if="isLoading" class="overlay">
                            <i class="fas fa-spinner fa-spin"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data(){
        return  {
            isLoading: false,
            form: new form({
                email:'',
                new_password:'',
                confirm_password:'',
                type:"Reset"
            })
        }
    },
    methods:{
        updatepassword(){
            if(this.validatefield()){
                this.isLoading = true;
                $('#error_msg').html('');
                this.form.post('change_password_for_user')
                .then((response) => {
                    let data = response;
                    if(data){
                        Toast.fire({
                            icon: 'success',
                            title: 'Password reset successfully'
                        })
                    }
                    else if(data = 'Null'){
                        Toast.fire({
                            icon: 'warning',
                            title: 'Please check email address'
                        });
                        $('#error_msg').html(response.data);
                    }
                    else{
                        Toast.fire({
                            icon: 'danger',
                            title: 'Not able to update password:'+response.data
                        });
                        $('#error_msg').html(response.data);
                    }
                })
                .catch((err) => {
                    console.log("Error:"+err)
                })
                .finally(() => {
                    this.isLoading = false;
                });
            }
        },
        validatefield(){
            let returntype=true;
            if(this.form.email==null || this.form.email==""){
                $('#email').addClass('is-invalid');
                $('#email').focus();
                $('#email_err').html('Please enter email');
                returntype=false;
            }

            if(this.form.new_password==null || this.form.new_password==""){
                $('#new_password').addClass('is-invalid');
                $('#new_password').focus();
                $('#new_password_err').html('Please metion new password');
                returntype=false;
            }
            if(this.form.current_password == this.form.new_password){
                $('#new_password').addClass('is-invalid');
                $('#new_password').focus();
                $('#new_password_err').html('Current password and new password cannot be same');
                returntype=false;
            }
            if(this.form.confirm_password==null || this.form.confirm_password==""){
                $('#confirm_password').addClass('is-invalid');
                $('#confirm_password').focus();
                $('#confirm_password_err').html('Please confirm password');
                returntype=false;
            }
            if(this.form.new_password!=this.form.confirm_password){
                $('#confirm_password').addClass('is-invalid');
                $('#confirm_password').focus();
                $('#confirm_password_err').html('New password and confirm password mismatch');
                returntype=false;
            }
            return returntype;
        },
        remove_error(fieldid,errid){
            if($('#'+fieldid).val()!=""){
                $('#'+fieldid).removeClass('is-invalid');
                $('#'+errid).html('');
            }
        },
    }
}
</script>
<style>
    .overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
    }
    .fa-spinner {
        font-size: 30px;
        color: #fff;
    }
</style>
