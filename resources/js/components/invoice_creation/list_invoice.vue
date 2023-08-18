<template>
    <div class="form-group table-responsive col-lg-12 col-md-12 col-sm-12 col-xs-12 mt-4 ">
        <table id="InvoiceList" cellspacing="0" width="100%" class="stripe cell-border order-column" border="1" style="border-color: rgb(235, 229, 229);border-collapse: collapse;" >
            <thead>
                <tr>
                    <th style='font-weight: bold;font-family: "Times New Roman", Times, serif'>Sl.No.</th>
                    <th style='font-weight: bold;font-family: "Times New Roman", Times, serif'>Bill Number</th>
                    <th style='font-weight: bold;font-family: "Times New Roman", Times, serif'>Entry Date</th>
                    <th style='font-weight: bold;font-family: "Times New Roman", Times, serif'>Work Order Number</th>
                    <th style='font-weight: bold;font-family: "Times New Roman", Times, serif'>Address To</th>
                    <th style='font-weight: bold;font-family: "Times New Roman", Times, serif'>Total Amount</th>
                    <th style='font-weight: bold;font-family: "Times New Roman", Times, serif'>Due Date</th>
                    <th style='font-weight: bold;font-family: "Times New Roman", Times, serif'>Bill Prepered By</th>
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
                    <td>Nu. {{ task.totalAmount }} </td>
                    <td>{{ task.due_date}} </td>
                    <td>{{ task.name}} </td>
                    <td style='font-weight: bold;font-family: "Times New Roman", Times, serif'>
                        <a v-if="system_role!=='View' && system_role!=='User'" href="#" class="btn btn-info btn-sm btn-flat text-white" @click="loadeditpage(task)"><i class="fa fa-edit"></i> Edit</a>
                        <a  v-if="system_role!=='View' && system_role!=='User'" href="#" class="btn btn-danger btn-sm btn-flat text-white" @click="Delete(task.id)"><i class="fa fa-trash"></i> Delete</a>
                        <a href="#" class="btn btn-info btn-sm btn-flat text-white" @click="Prinpage(task)"><i class="fa fa-print"></i> Print</a>
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
            system_role:'',
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
        Prinpage(data){ 
            this.$router.push({name:"PrintDetails",params:{data:data}});
        },

        //Loading all task list by userId
        InvoiceListDetails() {
            this.InvoiceList = [];
            let uri = 'InvoiceList';
            axios.get(uri)
                .then(response => {
                    let data = response.data;
                    this.InvoiceList = data; // Update InvoiceList with the modified data array
                })
                .catch((err) => {
                    console.log("Error: " + err);
                });
        },

        //filtering by parameter
        Filier(){
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
        },
        //deleting the task
        Delete(id){
            Swal.fire({
                title: 'Delete Confirmation',
                text: 'Are your sure you want to delete this Invoice?',
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
                if(result.isConfirmed){
                    axios.get('InvoiceDelete/'+id)
                    .then((response) => {
                        Toast.fire({
                        icon: 'success',
                        title: 'Invoice Deleted Successfully'
                        })
                        this.$router.push('/list_invoice')
                        this.InvoiceListDetails();
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
            this.userId=data.id;
            this.system_role=data.system_role;
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
