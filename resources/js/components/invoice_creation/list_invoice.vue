<template>
    <div class="form-group table-responsive col-lg-12 col-md-12 col-sm-12 col-xs-12 mt-4 ">
        <!-- <div class="row form-group">
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
        </div> -->
        <table id="InvoiceList" cellspacing="0" width="100%" class="stripe cell-border order-column" >
            <thead>
                <tr>
                    <th style='font-weight: bold;font-family: "Times New Roman", Times, serif'>Sl.No.</th>
                    <th style='font-weight: bold;font-family: "Times New Roman", Times, serif'>Bill Number</th>
                    <th style='font-weight: bold;font-family: "Times New Roman", Times, serif'>Entry Date</th>
                    <th style='font-weight: bold;font-family: "Times New Roman", Times, serif'>Work Order Number</th>
                    <th style='font-weight: bold;font-family: "Times New Roman", Times, serif'>Address To</th>
                    <th style='font-weight: bold;font-family: "Times New Roman", Times, serif'>Total Amount</th>
                    <th style='font-weight: bold;font-family: "Times New Roman", Times, serif'>Due Date</th>
                    <th style='font-weight: bold;font-family: "Times New Roman", Times, serif'>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(task, index) in InvoiceList" :key="index">
                    <td>{{ index + 1 }} </td>
                    <td>{{ task.Bill_no }}</td>
                    <td>{{ task.entry_date}} </td>
                    <td>{{ task.workorderNo}} </td>
                    <td>{{ task.addressTo}} </td>
                    <td>{{ task.totalAmount }} / </td>
                    <td>{{ task.due_date}} </td>
                    <td style='font-weight: bold;font-family: "Times New Roman", Times, serif'>
                        <a href="#" class="btn btn-info btn-sm btn-flat text-white" @click="loadeditpage(task)"><i class="fa fa-edit"></i> Edit</a>
                        <a v-if="task.ownership=='Own Task'" href="#" class="btn btn-danger btn-sm btn-flat text-white" @click="Delete(task.id)" :disabled="isLoading"><i class="fa fa-trash"></i> Delete</a>
                        <a href="#" class="btn btn-info btn-sm btn-flat text-white" @click="Viewpage(task)"><i class="fa fa-edit"></i> View</a>
                        <div v-if="isLoading" class="overlay">
                            <i class="fas fa-spinner fa-spin"></i>
                        </div>
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
            InvoiceList:[],
            dt:'',
            isLoading: false,
            form: new form({
                taskStatus:'',
                priority:'',
            }),
        }
    },
    methods: {
        //Redirect to edit page
        loadeditpage(data){
            this.$router.push({name:"edit_invoice",params:{data:data}});
        },
        Viewpage(data){ 
            this.$router.push({name:"view_invoice",params:{data:data}});
        },

        //Loading all task list by userId
        InvoiceListDetails(){
            this.isLoading = true;
            this.InvoiceList =[];
            let uri="";
                uri='InvoiceList';
                axios.get(uri)
                .then(response => {
                    let data = response.data;
                    this.InvoiceList = data;
                })
                .catch((err) => {
                console.log("Error:"+err)
            })
            .finally(() => {
                this.isLoading = false;
            });
            
        },

        //filtering by parameter
        Filier(){
            this.isLoading = true;
            this.InvoiceList =[];
            this.form.taskStatus='';
            this.form.priority='';
            this.form.taskStatus=$('#taskStatus').val();
            this.form.priority=$('#priority').val();
            let params =  this.form.taskStatus+'__'+this.form.priority;
            let uri="";
                uri='InvoiceListByParams/'+params;
                axios.get(uri)
                .then(response => {
                    let data = response.data;
                    this.InvoiceList = data;
                });
            this.isLoading = false;
        },
        //deleting the task
        Delete(id){
            Swal.fire({
                title: 'Delete Confirmation',
                text: 'Are your sure you want to delete this task?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Delete',
                cancelButtonText: 'Cancel',
                customClass: {
                popup: 'Delete-popup',
                title: 'Delete-title',
                text: 'Delete-text',
                confirmButton: 'Delete-confirm-button',
                cancelButton: 'Delete-cancel-button'
                }
            }).then((result) => {
                this.isLoading = true;
                if(result.isConfirmed){
                    axios.get('taskDelete/'+id)
                    .then((response) => {
                        Toast.fire({
                        icon: 'success',
                        title: 'Task Deleted Successfully'
                        })
                        this.$router.push('/list_task')
                        this.InvoiceListDetails();
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
        this.InvoiceListDetails();
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
        this.dt = $("#InvoiceList").DataTable({
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
        this.dt =  $("#InvoiceList").DataTable();
    },

    watch: {
        InvoiceList() {
            this.dt.destroy();
                this.$nextTick(()=>{
                    this.dt = $("#InvoiceList").DataTable({
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
        .Delete-popup {
            border-radius: 8px;
            background-color: #f5f5f5;
            border: none;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            }

            .Delete-title {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            }

            .Delete-text {
            font-size: 18px;
            color: #555;
            }

            .Delete-confirm-button {
            background-color: #3085d6;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            }

            .Delete-cancel-button {
            background-color: #d33;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            }

</style>
