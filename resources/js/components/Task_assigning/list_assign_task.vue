<template>
    <div class="form-group table-responsive col-lg-12 col-md-12 col-sm-12 col-xs-12 mt-4 ">
        <div class="row form-group">
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <label>Task Status:</label>
                    <select v-model="form.taskStatus" :class="{ 'is-invalid select2 select2-hidden-accessible': form.errors.has('taskStatus') }" class="form-control select2" name="taskStatus" id="taskStatus">
                        <option value=""> --Select--</option>
                        <option value="Pending">Pending</option>
                        <option value="Under_Process">Under Process</option>
                        <option value="Completed">Completed</option>
                    </select>
                    <has-error :form="form" field="taskStatus_err"></has-error>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <label>Priority Level:</label>
                    <select v-model="form.priority" :class="{ 'is-invalid select2 select2-hidden-accessible': form.errors.has('priority') }" class="form-control select2" name="priority" id="priority">
                        <option value=""> --Select--</option>
                        <option value="Low">Low</option>
                        <option value="Medium">Medium</option>
                        <option value="High">High</option>
                    </select>
                    <has-error :form="form" field="priority"></has-error>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 pt-4 mt-2">
                <button type="button" @click="Filier()" class="btn btn-success">
                <i class="fas fa-download" ></i> Fetch</button>
            </div>
        </div>
        <table id="taskList" cellspacing="0" width="100%" class="table table-bordered table-striped table-head-fixed" >
            <thead>
                <tr>
                    <th style='font-weight: bold;font-family: "Times New Roman", Times, serif'>Sl.No.</th>
                    <th style='font-weight: bold;font-family: "Times New Roman", Times, serif'>Title</th>
                    <th style='font-weight: bold;font-family: "Times New Roman", Times, serif'>Description</th>
                    <th style='font-weight: bold;font-family: "Times New Roman", Times, serif'>Priority Level</th>
                    <th style='font-weight: bold;font-family: "Times New Roman", Times, serif'>Task Status</th>
                    <th style='font-weight: bold;font-family: "Times New Roman", Times, serif'>Task Status</th>
                    <th style='font-weight: bold;font-family: "Times New Roman", Times, serif'>Task Owner</th>
                    <th style='font-weight: bold;font-family: "Times New Roman", Times, serif'>Date of created</th>
                    <th style='font-weight: bold;font-family: "Times New Roman", Times, serif;width:25%'>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(task, index) in tasklist" :key="index">
                    <td>{{ index + 1 }} </td>
                    <td>{{ task.title }} {{ task.MiddleName }} {{ task.LastName}}</td>
                    <td>{{ task.description}} </td>
                    <td v-if="task.priority=='High'"><span class="badge badge-danger">{{ task.priority}}</span></td>
                    <td v-else-if="task.priority=='Medium'"><span class="badge badge-warning">{{ task.priority}}</span></td>
                    <td v-else><span class="badge badge-info">{{ task.priority}}</span></td>

                    <td v-if="task.taskStatus=='Completed'"><span class="badge badge-success">{{ task.taskStatus}}</span></td>
                    <td v-else-if="task.taskStatus=='Under Process'"><span class="badge badge-info">{{ task.taskStatus}}</span></td>
                    <td v-else><span class="badge badge-warning">{{ task.taskStatus}}</span></td>
                    <td>{{ task.due_date}} </td>
                    <td>Own Task </td>
                    <td>{{ task.created_at}} </td>
                    <td style='font-weight: bold;font-family: "Times New Roman", Times, serif;width:25%'>
                        <a href="#" class="btn btn-info btn-sm btn-flat text-white" @click="loadeditpage(task)"><i class="fa fa-edit"></i> Edit</a>
                        <a href="#" class="btn btn-danger btn-sm btn-flat text-white" @click="Delete(task.id)"><i class="fa fa-trash"></i> Delete</a>
                        <a href="#" class="btn btn-success btn-sm btn-flat text-white" @click="AssignWork(task.id)"><i class="fa fa-user"></i> Assign Task</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
<script>
export default {
    components: {
    },
    data() {
        return {
            tasklist:[],
            dt:'',
            form: new form({
                taskStatus:'',
                priority:'',
            }),
        }
    },
    methods: {
        //Redirect to edit page
        loadeditpage(data){
            this.$router.push({name:"edit_task",params:{data:data}});
        },
        
        //Redirect to task assign page
        AssignWork(data){
            this.$router.push({name:"edit_task",params:{data:data}});
        },
        //Loading all task list by userId
        tasklistDetails(){
            this.tasklist =[];
            let uri="";
                uri='tasklist';
                axios.get(uri)
                .then(response => {
                    let data = response.data;
                    this.tasklist = data;
                });
        },

        //filtering by parameter
        Filier(){
            this.tasklist =[];
            this.form.taskStatus='';
            this.form.priority='';
            this.form.taskStatus=$('#taskStatus').val();
            this.form.priority=$('#priority').val();
            let params =  this.form.taskStatus+'__'+this.form.priority;
            let uri="";
                uri='tasklistByParams/'+params;
                axios.get(uri)
                .then(response => {
                    let data = response.data;
                    this.tasklist = data;
                });
        },
        //deleting the task
        Delete(id){
            Swal.fire({
                text: "Are you sure you want to delete this task?",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!',
            }).then((result) => {
                if(result.isConfirmed){
                    axios.get('taskDelete/'+id)
                    .then((response) => {
                        Toast.fire({
                        icon: 'success',
                        title: 'Task Deleted Successfully'
                        })
                        this.$router.push('/list_task')
                        location.reload();
                    })
                    .catch((error) => {
                        console.log("Error remove:"+error);
                    })
                }
            });
        },
        //Function to pick value from id in select2
        changefunction(id){
            if($('#'+id).val()!=""){
                $('#'+id).removeClass('is-invalid select2');
                $('#'+id+'_err').html('');
                $('#'+id).addClass('select2');
            }
            if(id=="taskStatus"){
                this.form.taskStatus=$('#taskStatus').val();
            }
            if(id=="priority"){
                this.form.priority=$('#priority').val();
            }
        }
    },
    
    async mounted(){
        this.tasklistDetails();
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
        //loading sessions details
        axios.get('getSessionDetail')
        .then(response => {
            let data = response.data.data;
            this.userId=data.id;;
        })
        // this.dt =  $("#annoucementList").DataTable();
        this.dt = $("#taskList").DataTable({
            "order": [[ 0, "asc" ]],
            "lengthChange": false,
            "searching": false,
        }) 
    },

    watch: {
        tasklist() {
            this.dt.destroy();
            this.$nextTick(() => {
                this.dt =  $("#taskList").DataTable()
            });
        }
    },
}
</script>
