<template>
    <div class="container-fluid">
        <div class="card card-primary card-outline">
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
                            <input type="text" class="form-control" rows="3" @change="remove_error('title')" :class="{ 'is-invalid': form.errors.has('title') }" id="title" v-model="form.title" placeholder="Title" disabled/>
                            <has-error :form="form" field="title"></has-error>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label for="due_date">Due Date:<span class="text-danger">*</span></label>
                            <input type="date" v-model="form.due_date" id="due_date" class="form-control custom-input"  name="due_date">
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Assign Task To:<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" rows="3" @change="remove_error('Email')" :class="{ 'is-invalid': form.errors.has('Email') }" id="Email" v-model="form.Email" placeholder="Enter Email"/>
                            <has-error :form="form" field="Email"></has-error>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 pt-4 mt-2">
                            <button type="button" @click="Search()" class="btn btn-success">
                            <i class="fas fa-search" ></i> Search</button>
                        </div>
                    </div>
                    <div class="form-group table-responsive col-lg-12 col-md-12 col-sm-12 col-xs-12 mt-4 " id="content">
                        <div class="row form-group ">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 basicD">
                                <label>Name: <b>{{ this.name }}</b></label>
                                
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 basicD">
                                <label>Email Address: <b>{{ this.email }}</b></label>
                            </div>
                        </div>
                        <label>User Task Details</label>
                        <div class="row form-group">
                            <table id="taskList" cellspacing="0" width="100%" class="table table-bordered table-striped table-head-fixed" >
                                <thead>
                                    <tr>
                                        <th style='font-weight: bold;font-family: "Times New Roman", Times, serif'>Sl.No.</th>
                                        <th style='font-weight: bold;font-family: "Times New Roman", Times, serif'>Title</th>
                                        <th style='font-weight: bold;font-family: "Times New Roman", Times, serif'>Priority Level</th>
                                        <th style='font-weight: bold;font-family: "Times New Roman", Times, serif'>Task Status</th>
                                        <th style='font-weight: bold;font-family: "Times New Roman", Times, serif'>Description</th>
                                        <th style='font-weight: bold;font-family: "Times New Roman", Times, serif'>Due Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(task, index) in userDetails.TaskDetails" :key="index">
                                        <td>{{ index + 1 }} </td>
                                        <td>{{ task.title }}</td>
                                        <td v-if="task.priority=='High'"><span class="badge badge-danger">{{ task.priority}}</span></td>
                                        <td v-else-if="task.priority=='Medium'"><span class="badge badge-warning">{{ task.priority}}</span></td>
                                        <td v-else><span class="badge badge-info">{{ task.priority}}</span></td>
                                        <td v-if="task.taskStatus=='Completed'"><span class="badge badge-success">{{ task.taskStatus}}</span></td>
                                        <td v-else-if="task.taskStatus=='Under Process'"><span class="badge badge-info">{{ task.taskStatus}}</span></td>
                                        <td v-else><span class="badge badge-warning">{{ task.taskStatus}}</span></td>
                                        <td>{{ task.description}} </td>
                                        <td>{{ task.due_date}} </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card-footer">
            <div class="row form-group fa-pull-right">
                <div class="col-md-12" id="assign">
                    <!-- <button type="button" class="btn btn-flat btn-primary" @click="AssignTask()"> <i class="fa fa-edit" ></i> Assign Task</button> -->
                    <button type="button" class="btn btn-flat btn-primary" @click="AssignTask()" :disabled="isLoading">
                        <i class="fa fa-edit"></i> Assign Task
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
            name:'',
            email:'',
            userDetails:'',
            currentDate:'',
            isLoading: false,
            form: new form({
                id:'',
                Email:'',
                type : 'Re_assign',
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
        //Searching User for assigning task
        Search(){
            this.userDetails=[];
            $('#assign').show();
            $('#content').show();
            this.form.Email=$('#Email').val();
            let params = this.form.Email+'__'+this.form.type;
            let uri="";
                uri='SearchUserByEmail/'+params;
                axios.get(uri)
                .then(response => {
                    let data = response.data;
                    if(data ==='Exist'){
                        Swal.fire({
                            text: 'Sorry! ' + this.form.Email +" is not registered in the system",
                            icon: 'info',
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Okay',
                        });
                    }
                    if(data ==='OwnEmail'){
                        Swal.fire({
                            text: 'Sorry! You cannot assign task to your self',
                            icon: 'info',
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Okay',
                        });
                        $('#assign').hide();
                        $('#content').hide();
                        
                    }
                    else{
                        this.userDetails = data;
                        this.name= data.name;
                        this.AssUserId= data.id;
                        this.email = data.email;
                    }
                });
        },
        AssignTask(){
            this.isLoading = true;
            let parameters = this.form.parent_id+'__'+this.AssUserId+'__'+this.email+'__'+this.form.type+'__'+this.form.id+'__'+this.name;
            this.form.post('Assign_task/'+parameters)
            .then(() => {
                    Toast.fire({
                        icon: 'success',
                        title: 'Task Assigned Successfully'
                    })
                    this.$router.push('/assigned_list_task');
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
        $('#assign').hide();
        $('#content').hide();
        //To pick current date in format yyyy-mm-dd
        const currentDate = new Date();
        const year = currentDate.getFullYear();
        const month = String(currentDate.getMonth() + 1).padStart(2, '0');
        const day = String(currentDate.getDate()).padStart(2, '0');
        this.currentDate = `${year}-${month}-${day}`;

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
        this.form.parent_id=this.$route.params.data.parent_id;
        this.form.title=this.$route.params.data.title;
        this.form.description=this.$route.params.data.description;
        this.form.priority=this.$route.params.data.priority;
        this.form.due_date=this.$route.params.data.due_date;
        this.form.taskStatus=this.$route.params.data.taskStatus;
    },
}
</script>
<style>
    .basicD{
            border-radius: 15px;
            box-shadow: 0 0 3px rgba(0, 0, 0, 0.1);
            padding: 5px;
            font-size: 15px;
            background-color: rgb(228, 232, 235);
            text-shadow: 0 0 10px rgba(247, 249, 250, 0.7);
            animation: glow 1.5s ease-in-out infinite alternate;
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
