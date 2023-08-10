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
                            <div class="
                            col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Title:<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" rows="3" @change="remove_error('title')" :class="{ 'is-invalid': form.errors.has('title') }" id="title" v-model="form.title" placeholder="Title"/>
                                <has-error :form="form" field="title"></has-error>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label for="due_date">Due Date:<span class="text-danger">*</span></label>
                                <input type="date" v-model="form.due_date" id="due_date" class="form-control custom-input"  name="due_date">
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
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Task Status:<span class="text-danger">*</span></label>
                                <select v-model="form.taskStatus" :class="{ 'is-invalid select2 select2-hidden-accessible': form.errors.has('taskStatus') }" class="form-control select2" name="taskStatus" id="taskStatus">
                                    <option value=""> --Select--</option>
                                    <option value="Pending">Pending</option>
                                    <option value="Under Process">Under Process</option>
                                    <option value="Completed">Completed</option>
                                </select>
                                <has-error :form="form" field="taskStatus"></has-error>
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
                        <button type="button" class="btn btn-flat btn-primary" @click="SaveDetails()" :disabled="isLoading">
                            <i class="fa fa-edit"></i> Update
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
                id:'',
                title:'',
                description:'',
                priority:'',
                taskStatus:'',
                due_date:'',
                action :'edit',
            })
        }
    },
    methods:{
        changefunction(id){
            if($('#'+id).val()!=""){
                $('#'+id).removeClass('is-invalid select2');
                $('#'+id+'_err').html('');
                $('#'+id).addClass('select2');
            }
            if(id=="priority"){
                this.form.priority=$('#priority').val();
            }
            if(id=="taskStatus"){
                this.form.taskStatus=$('#taskStatus').val();
            }
        },
        SaveDetails(){
            this.isLoading = true;
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

    },
    created(){
        this.form.status=this.$route.params.data.status;
        this.form.id=this.$route.params.data.id;
        this.form.title=this.$route.params.data.title;
        this.form.description=this.$route.params.data.description;
        this.form.priority=this.$route.params.data.priority;
        this.form.due_date=this.$route.params.data.due_date;
        this.form.taskStatus=this.$route.params.data.taskStatus;
    },
}
</script>
<style>
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
