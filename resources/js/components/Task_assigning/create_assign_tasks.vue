<template>
    <div class="container-fluid">
        <div class="card card-primary card-outline" id="newworkflow">
            <div class="card-body">
                <div class="bootbox-body">
                    <form class="change-password-form">
                        <div class="row form-group">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <span class="text-danger" id="error_msg"></span>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Title:<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" rows="3" @change="remove_error('title')" :class="{ 'is-invalid': form.errors.has('title') }" id="title" v-model="form.title" placeholder="Title"/>
                                <has-error :form="form" field="title"></has-error>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Due Date:<span class="text-danger">*</span></label>
                                    <input type="text" v-model="form.due_date" @focus="showDatePicker" readonly>
                                <has-error :form="form" field="date"></has-error>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Priority Level:<span class="text-danger">*</span></label>
                                <select v-model="form.priority" :class="{ 'is-invalid select2 select2-hidden-accessible': form.errors.has('priority') }" class="form-control select2" name="priority" id="priority">
                                    <option value=""> --Select--</option>
                                    <option value="Low">Low</option>
                                    <option value="Medium">Medium</option>
                                    <option value="High">High</option>
                                </select>
                                <has-error :form="form" field="priority"></has-error>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label>Description:<span class="text-danger">*</span></label>
                                    <textarea class="form-control" v-model="form.description" :class="{ 'is-invalid': form.errors.has('description')}" id="description"  @change="remove_error('description')"></textarea>
                                <has-error :form="form" field="description"></has-error>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-footer">
                <div class="row form-group fa-pull-right">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-flat btn-primary" @click="SaveDetails()"> <i class="fa fa-save"></i> Add Task</button>
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
            form: new form({
                title:'',
                description:'',
                priority:'',
                taskStatus:'Pending',
                due_date:'2023-06-29',
                action :'add',
            })
        }
    },
    methods:{
        showDatePicker() {
            alert("here");
      // Implement your date picker logic here
      // You can use a third-party library like vue-datepicker or implement a custom date picker
        },
        SaveDetails(){
            this.form.post('create_tasks')
            .then(() => {
                    Toast.fire({
                        icon: 'success',
                        title: 'Details Updated Successfully'
                    })
                    this.$router.push('/list_task');
            })
            .catch((err) => {
                console.log("Error:"+err)
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
</style>
