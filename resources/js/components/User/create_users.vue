<template>
    <div class="container-fluid">
        <div class="card card-primary card-outline" id="newworkflow">
            <div class="card-body">
                <div class="bootbox-body">
                    <form class="change-password-form">
                        <div class="alert alert-info alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <div id="message"></div>
                        </div>
                        <div class="row form-group">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Email Address:<span class="text-danger">*</span> <small><i>(Press Enter key )</i></small></label>
                                <input type="text" class="form-control" rows="3" @change="remove_error('email')" :class="{ 'is-invalid': form.errors.has('email') }" id="email" v-model="form.email" placeholder="Email Address" @keyup.enter="onEnterPressed" />
                                <has-error :form="form" field="email"></has-error>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Name:<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" rows="3" @change="remove_error('name')" :class="{ 'is-invalid': form.errors.has('name') }" id="name" v-model="form.name" placeholder="Name"/>
                                <has-error :form="form" field="name"></has-error>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Contact Number:</label>
                                <input type="number" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength = "8" minlength = "8" class="form-control" @change="remove_error('contact_no')" :class="{ 'is-invalid': form.errors.has('contact_no') }" id="contact_no" v-model="form.contact_no" placeholder="Contact No">
                                <has-error :form="form" field="contact_no"></has-error>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Password:<span class="text-danger">*</span></label>
                                <input name="password" id="password" @change="remove_error('password','password_err')" class="form-control" v-model="form.password"  type="password" placeholder="Password">
                                <span class="text-danger" id="password_err"></span>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Confirm Password:<span class="text-danger">*</span></label>
                                <input name="confirm_password" id="confirm_password" @change="remove_error('confirm_password','confirm_password_err')" class="form-control" v-model="form.confirm_password"  type="password" placeholder="Confirm Password">
                                <span class="text-danger" id="confirm_password_err"></span>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Select Company:<span class="text-danger">*</span></label>
                                <select v-model="form.org_id" :class="{ 'is-invalid select2 select2-hidden-accessible': form.errors.has('org_id') }" class="form-control select2" name="org_id" id="org_id">
                                    <option value=""> --Select--</option>
                                    <option v-for="(item, index) in orgList" :key="index" v-bind:value="item.id" v-if="item.id=='8070fd6f-fc67-4379-99c3-048e325f7d27'">{{ item.name}}</option>
                                </select>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>System User:<span class="text-danger">*</span></label>
                                <select v-model="form.role" :class="{ 'is-invalid select2 select2-hidden-accessible': form.errors.has('role') }" class="form-control select2" name="role" id="role">
                                    <option value=""> --Select--</option>
                                    <option value="Admin">Admin</option>
                                    <option value="User">User</option>
                                    <option value="View">Report Viewer</option>
                                    
                                </select>
                                <has-error :form="form" field="role"></has-error>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-footer">
                <div class="row form-group fa-pull-right">
                    <div class="col-md-12" id="add">
                        <button type="button" class="btn btn-flat btn-primary" @click="SaveDetails()" :disabled="isLoading"> <i class="fa fa-save"></i> Add User</button>
                    </div>
                    <div v-if="isLoading" class="overlay">
                        <i class="fas fa-spinner fa-spin"></i>
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
            orgList:[],
            isLoading : false,
            form: new form({
                name:'',
                contact_no:'',
                password:'',
                confirm_password:'',
                email:'',
                org_id:'',
                role:'',
                action :'add',
            })
        }
    },
    methods:{
        //pulling organization List
        LoadingOrgList(){
        let uri="";
            uri='orgListDetails';
            axios.get(uri)
            .then(response => {
                let data = response.data;
                // for(let i=0; i<data.length;i++){
                //     if(data[i].id='8070fd6f-fc67-4379-99c3-048e325f7d27'){
                //         this.orgList.p
                //     }
                // }
                this.orgList = data;
            });
        },
        //saving user details
        SaveDetails(){
            if (this.validatefield()) {
                $('#error_msg').html('');
                this.isLoading = true;
                this.form.post('add_newuser')
                .then(() => {
                        Toast.fire({
                            icon: 'success',
                            title: 'User added Successfully'
                        })
                        this.$router.push('/list_users');
                })
                .catch((err) => {
                    console.log("Error:"+err)
                })
                .finally(() => {
                    this.isLoading = false;
                });
            }
        },
        onEnterPressed(){
            this.form.email = $('#email').val();
            //checking for email if it is already registered or not in the system
            let uri="";
            uri='emailchecking/'+this.form.email;
            axios.get(uri)
            .then(response => {
                let data = response.data;
                if(data){
                    $('#message').html("<b style='color:red'>Apologies, but this email address is already registered in our system. Thank you. </b>");
                    $('#add').hide();
                    $('.alert-dismissible').show();
                }
                else{
                    $('#message').html("<b style='color:green'>Congratulations! This email has not been previously recorded in our system. Please proceed with filling in the remaining details. Thank you. </b>");
                    $('#add').show();
                    $('.alert-dismissible').show();
                }
                console.log(data);
            });
            
        },

        remove_error(field_id){
            if($('#'+field_id).val()!=""){
                $('#'+field_id).removeClass('is-invalid');
                $('#'+field_id+'_err').html('');
            }
        },
        changefunction(id){
            if($('#'+id).val()!=""){
                $('#'+id).removeClass('is-invalid select2');
                $('#'+id+'_err').html('');
                $('#'+id).addClass('select2');
            }
            if(id=="org_id"){
                this.form.org_id=$('#org_id').val();
            }
            if(id=="role"){
                this.form.role=$('#role').val();
            }
        },

        //validing the password and confirm password is same
        validatefield(){
            let returntype=true;
            if(this.form.email==null || this.form.email==""){
                $('#email').addClass('is-invalid');
                $('#email').focus();
                $('#email_err').html('Please enter email');
                returntype=false;
            }

            if(this.form.password==null || this.form.password==""){
                $('#password').addClass('is-invalid');
                $('#password').focus();
                $('#password_err').html('Please metion new password');
                returntype=false;
            }
            if(this.form.current_password == this.form.password){
                $('#password').addClass('is-invalid');
                $('#password').focus();
                $('#password_err').html('Current password and new password cannot be same');
                returntype=false;
            }
            if(this.form.confirm_password==null || this.form.confirm_password==""){
                $('#confirm_password').addClass('is-invalid');
                $('#confirm_password').focus();
                $('#confirm_password_err').html('Please confirm password');
                returntype=false;
            }
            if(this.form.password!=this.form.confirm_password){
                $('#confirm_password').addClass('is-invalid');
                $('#confirm_password').focus();
                $('#confirm_password_err').html('New password and confirm password mismatch');
                returntype=false;
            }
            if ($('#password').val().length < 8) {
                $('#password').addClass('is-invalid');
                $('#password').focus();
                $('#password_err').html('Password must be a minimum of 8 characters');
                return false;
            }

            return returntype;
        },
    },
    async mounted(){
        $('.alert-dismissible').hide();
        this.LoadingOrgList();
        // $('#date').val(this.formatDateToddmmyyyy(this.form.due_date));
        $('.select2').select2();
        $('.select2').select2({
            theme: 'bootstrap4'
        });
        $('.select2').on('select2:select', function (el){
            Fire.$emit('changefunction',$(this).attr('id'));
        });
        Fire.$on('changefunction',(id)=> {
            this.changefunction(id);
        });

    }
}
</script>
<style>
    .bootbox-body{
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        padding: 10px;
        background-color: #f8f9fa;
    }
    .custom-input {
        border: 1px solid #ccc;
        border-radius: 5px;
        padding: 8px;
        font-size: 16px;
        color: #333;
    }

    .custom-input:focus {
        outline: none;
        border-color: #4c8bf5; /* Change to your preferred color */
        box-shadow: 0 0 5px rgba(76, 139, 245, 0.5); /* Change to your preferred shadow */
    }
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
