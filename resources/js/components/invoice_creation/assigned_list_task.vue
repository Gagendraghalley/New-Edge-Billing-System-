<template id="app">
    <div class="form-group table-responsive col-lg-12 col-md-12 col-sm-12 col-xs-12 mt-4 ">
        <table id="taskList" cellspacing="0" width="100%" class="stripe cell-border order-column">
            <thead>
                <tr>
                    <th style='font-weight: bold;font-family: "Times New Roman", Times, serif'>Sl.No.</th>
                    <th style='font-weight: bold;font-family: "Times New Roman", Times, serif'>Title</th>
                    <th style='font-weight: bold;font-family: "Times New Roman", Times, serif'>Description</th>
                    <th style='font-weight: bold;font-family: "Times New Roman", Times, serif'>Priority Level</th>
                    <th style='font-weight: bold;font-family: "Times New Roman", Times, serif'>Task Status</th>
                    <th style='font-weight: bold;font-family: "Times New Roman", Times, serif'>Due Date</th>
                    <th style='font-weight: bold;font-family: "Times New Roman", Times, serif'>Assigned to</th>
                    <th style='font-weight: bold;font-family: "Times New Roman", Times, serif'>Date of created</th>
                    <th style='font-weight: bold;font-family: "Times New Roman", Times, serif'>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(task, index) in tasklist" :key="index">
                    <td>{{ index + 1 }} </td>
                    <td>{{ task.title }}</td>
                    <td>{{ task.description}} </td>
                    <td v-if="task.priority=='High'"><span class="badge badge-danger">{{ task.priority}}</span></td>
                    <td v-else-if="task.priority=='Medium'"><span class="badge badge-warning">{{ task.priority}}</span></td>
                    <td v-else><span class="badge badge-info">{{ task.priority}}</span></td>

                    <td v-if="task.taskStatus=='Completed'"><span class="badge badge-success">{{ task.taskStatus}}</span></td>
                    <td v-else-if="task.taskStatus=='Under Process'"><span class="badge badge-info">{{ task.taskStatus}}</span></td>
                    <td v-else><span class="badge badge-warning">{{ task.taskStatus}}</span></td>
                    <td>{{ task.due_date}} </td>
                    <td>{{ task.ownership }} </td>
                    <td>{{ task.created_at}} </td>
                    <td style='font-weight: bold;font-family: "Times New Roman", Times, serif'>
                        <a href="#" class="btn btn-danger btn-sm btn-flat text-white" @click="Delete(task.parent_id)" :disabled="isLoading"><i class="fa fa-trash"></i> Delete</a>
                        <div v-if="isLoading" class="overlay">
                            <i class="fas fa-spinner fa-spin"></i>
                        </div>
                        <a href="#" class="btn btn-success btn-sm btn-flat text-white" @click="ReAssignWork(task)"><i class="fa fa-user"></i> Re-Assign Task</a>
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
            isLoading: false,
            form: new form({

            }),
        }
    },
    methods: {
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
                this.isLoading = true;
                if(result.isConfirmed){
                    axios.get('taskDeleteReAssigned/'+id)
                    .then((response) => {
                        Toast.fire({
                        icon: 'success',
                        title: 'Task Deleted Successfully'
                        })
                        this.$router.push('/assigned_list_task')
                        this.tasklistDetails();
                    })
                    .catch((error) => {
                        console.log("Error remove:"+error);
                    })
                    .finally(() => {
                        this.isLoading = false;
                    });
                }
            });
        },
        //Redirect to edit page
        loadeditpage(data){
            this.$router.push({name:"edit_task",params:{data:data}});
        },

        //Redirect to task assign page
        ReAssignWork(data){
            this.$router.push({name:"edit_re_assign_task",params:{data:data}});
        },
        //Loading all task list by userId
        tasklistDetails(){
            this.tasklist =[];
            this.isLoading = true;
            let uri="";
                uri='Assigntasklist';
                axios.get(uri)
                .then(response => {
                    let data = response.data;
                    this.tasklist = data;
            });
            this.isLoading = false;
        },
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
        this.dt = $("#taskList").DataTable({
            "responsive": false,
            "autoWidth": true,
            scrollY:        "300px",
            scrollX:        true,
            scrollCollapse: true,
            paging:         false,
            forceHide: true,
            searching: true,
            fixedColumns:   {
                leftColumns: 2
            },
        });
        this.dt =  $("#taskList").DataTable();
    },

    watch: {
        tasklist() {
            this.dt.destroy();
                this.$nextTick(()=>{
                    this.dt = $("#taskList").DataTable({
                    "responsive": false,
                    "autoWidth": true,
                    scrollY:        "300px",
                    scrollX:        true,
                    scrollCollapse: true,
                    paging:         false,
                    forceHide: true,
                    searching: true,
                    fixedColumns:   {
                        leftColumns: 2
                    },
                    })
                });
            },
    },
}
</script>
<style scoped>
        th, td { white-space: nowrap; }
        div.dataTables_wrapper {
            width: 900px;
            margin: 0 auto;
        }
        table.DTFC_Cloned thead,table.DTFC_Cloned tfoot{background-color:white}div.DTFC_Blocker{background-color:white}div.DTFC_LeftWrapper table.dataTable,div.DTFC_RightWrapper table.dataTable{margin-bottom:0;z-index:2}div.DTFC_LeftWrapper table.dataTable.no-footer,div.DTFC_RightWrapper table.dataTable.no-footer{border-bottom:none}table.dataTable.display tbody tr.DTFC_NoData{background-color:transparent}div.DTFC_LeftBodyLiner{overflow-x: hidden}
        .underline {
            text-decoration: underline;
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
