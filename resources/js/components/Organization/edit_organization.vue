<template>
    <div class="container-fluid">
        <div class="card card-primary card-outline" id="newworkflow">
            <div class="card-body">
                <div class="bootbox-body">
                    <form class="change-password-form">
                        <div class="row form-group">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Organization Name:<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" rows="3" @change="remove_error('name')" :class="{ 'is-invalid': form.errors.has('name') }" id="name" v-model="form.name" placeholder="Organization Name"/>
                                <has-error :form="form" field="name"></has-error>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Organization Code:<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" rows="3" @change="remove_error('org_code')" :class="{ 'is-invalid': form.errors.has('org_code') }" id="org_code" v-model="form.org_code" placeholder="Organization  Code"/>
                                <has-error :form="form" field="org_code"></has-error>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label>Description:<span class="text-danger">*</span></label>
                                    <textarea class="form-control" v-model="form.description" :class="{ 'is-invalid': form.errors.has('description')}" id="description"  @change="remove_error('description')" placeholder="Description"></textarea>
                                <has-error :form="form" field="description"></has-error>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-footer">
                <div class="row form-group fa-pull-right">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-flat btn-primary" @click="UpdateDetails()" :disabled="isLoading"> <i class="fa fa-edit"></i> Update Details</button>
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
                name:'',
                org_code:'',
                description:'',
                action :'edit',
            })
        }
    },
    methods:{
        UpdateDetails(){
            this.isLoading = true;
            this.form.post('add_organization')
            .then(() => {
                    Toast.fire({
                        icon: 'success',
                        title: 'Organization Details updated Successfully'
                    })
                    this.$router.push('/list_organization');
            })
            .catch((err) => {
                console.log("Error:"+err)
            })
        .finally(() => {
            this.isLoading = false;
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
            if(id=="priority"){
                this.form.priority=$('#priority').val();
            }
        }
    },
    async mounted(){
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

    },
    created(){
        this.form.id=this.$route.params.data.id;
        this.form.name=this.$route.params.data.name;
        this.form.description=this.$route.params.data.description;
        this.form.org_code=this.$route.params.data.org_code;
    },
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
