<template>
    <div class="form-group table-responsive col-lg-12 col-md-12 col-sm-12 col-xs-12 mt-4 ">
        <div class="form-row">
            <!-- From Date -->
            <div class="form-group col-md-4">
                <label for="from_date">From Date:<span class="text-danger">*</span></label>
                <input type="date" v-model="form.from_date" id="from_date" class="form-control" @change="validateFromDate" :class="{ 'is-invalid': form.errors.has('from_date') }" name="from_date" required>
                <has-error :form="form" field="from_date_err"></has-error>
            </div>
            <!-- To Date -->
            <div class="form-group col-md-4">
                <label for="to_date">To Date:<span class="text-danger">*</span></label>
                <input type="date" v-model="form.to_date" id="to_date" class="form-control" @change="validateToDate" :class="{ 'is-invalid': form.errors.has('to_date') }" name="to_date" required>
                <has-error :form="form" field="to_date_err"></has-error>
            </div>
            <!-- Status -->
            <div class="form-group col-md-4">
                <label for="status">Bill Status: <span class="text-danger">*</span></label>
                    <select v-model="form.status" @change="validateStatus" :class="{ 'is-invalid select2 select2-hidden-accessible': form.errors.has('status') }" class="form-control select2" id="status" required>
                        <option value="">-- Select --</option>
                        <option value="Received">Bill(s) Received</option>
                        <option value="Not_Received">Bill(s) Not Received</option>
                        <option value="All">All Bill(s)</option>
                    </select>
                <has-error :form="form" field="status_err"></has-error>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-2 pt-4">
                <button type="button" @click="Filter()" class="btn btn-primary rounded-pill shadow">
                    <i class="fas fa-cloud-download-alt me-2"></i> Fetch Data
                </button>
            </div>
        </div>
        <table id="BillList" cellspacing="0" width="100%" class="stripe cell-border order-column" border="1" style="border-color: rgb(235, 229, 229);border-collapse: collapse;">
            <thead>
                <tr>
                    <th style='font-weight: bold;font-family: "Times New Roman", Times, serif'>Sl.No.</th>
                    <th style='font-weight: bold;font-family: "Times New Roman", Times, serif'>Bill Number</th>
                    <th style='font-weight: bold;font-family: "Times New Roman", Times, serif'>Total Amount</th>
                    <th style='font-weight: bold;font-family: "Times New Roman", Times, serif'>Entry Date</th>
                    <th style='font-weight: bold;font-family: "Times New Roman", Times, serif'>Due Date</th>
                    <th style='font-weight: bold;font-family: "Times New Roman", Times, serif'>Bill Status</th>
                    <th style='font-weight: bold;font-family: "Times New Roman", Times, serif'>Number of Day(s)</th>
                    <th style='font-weight: bold;font-family: "Times New Roman", Times, serif'>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(task, index) in BillList" :key="index">
                    <td>{{ index + 1 }} </td>
                    <td>{{ task.Bill_no }}</td>
                    <td>Nu. {{ task.totalAmount }} </td>
                    <td>{{ task.entry_date }} </td>
                    <td>{{ task.due_date }} </td>
                    <td v-if="task.status == 'Pending'"><span class="text-info">Not Received</span></td>
                    <td v-else><span class="text-success">Received</span></td>

                    <td v-if="task.status=='Pending'">
                        <span :style="{ color: calculateDateDifference(task.entry_date, task.due_date) < 0 ? 'red' : 'black' }">
                            {{ calculateDateDifference(task.entry_date, task.due_date) < 0 ? ' ' + Math.abs(calculateDateDifference(task.entry_date, task.due_date)) + ' day(s) Overdue(s)' : calculateDateDifference(task.entry_date, task.due_date) + ' day(s) left' }}
                        </span>
                    </td>
                    <td v-else>
                        <span :style="{ color: calculateDateDifference(task.received_date, task.due_date) < 0 ? 'red' : 'black' }">
                            {{ calculateDateDifference(task.received_date, task.due_date) < 0 ? 'Overdue(s) By' + Math.abs(calculateDateDifference(task.received_date, task.due_date)) + ' Day(s) ' : 'Received Before ' +calculateDateDifference(task.received_date, task.due_date) + ' Day(s) ' }}
                        </span>
                    </td>
                    <td style="font-weight: bold; font-family: 'Times New Roman', Times, serif">
                        <a v-if="task.status === 'Pending'" href="#" class="btn btn-info btn-sm btn-flat text-white" @click="Recieved(task.id)"><i class="fa fa-edit"></i> Received</a>
                        <a v-else :href="'storage/' + task.bill_file" download>
                            <i class="fa fa-download"> </i> {{ task.bill_file }}
                        </a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
<script>
import Errors from '../../Errors.js';
export default {

    components: {
    },
    data() {
        return {
            BillList:[],
            dt:'',
            isLoading: false,
            form: new form({
                from_date:'',
                to_date:'',
                status:'',
                errors: new Errors(),
            }),
        }
    },
    methods: {
        calculateDateDifference(entryDate, dueDate) {
            const entryDateObj = new Date(entryDate);
            const dueDateObj = new Date(dueDate);
            
            const timeDiff = dueDateObj - entryDateObj;
            const daysDiff = Math.floor(timeDiff / (1000 * 3600 * 24));
            
            return daysDiff;
        },
        Prinpage(data){ 
            this.$router.push({name:"PrintDetails",params:{data:data}});
        },

        //Loading all task list by userId
        BillListDetails(){
            this.BillList =[];
            let uri="";
                uri='BillList';
                axios.get(uri)
                .then(response => {
                    let data = response.data;
                    this.BillList = data;
                })
                .catch((err) => {
                console.log("Error:"+err)
            })
            ;
            
        },

        //filtering by parameter
        Filter() {
            this.BillList = [];
            //Calling methods to validate
            this.validateFromDate();
            this.validateToDate();
            this.validateStatus()

            let params = this.form.status + '__' + this.form.from_date + '__' + this.form.to_date;
            let uri = 'BillListByParams/' + params;

            // Clear any previous data before making the new request
            this.BillList = [];

            axios.get(uri)
                .then(response => {
                    let data = response.data;
                    this.BillList = data;
                });
        },
        validateFromDate() {
            if (!this.form.from_date) {
                this.form.errors.add('from_date', 'From Date is required.');
            } else {
                this.form.errors.remove('from_date');
            }
        },
        validateToDate() {
            if (!this.form.to_date) {
                this.form.errors.add('to_date', 'To Date is required.');
            } else {
                this.form.errors.remove('to_date');
            }
        },
        validateStatus() {
            if (!this.form.status) {
                this.form.errors.add('status', 'Bill Status is required.');
            } else {
                this.form.errors.remove('status');
            }
        },

        Recieved(id) {
            let fileInput = null; // Reference to the file input element

            const fileUploadHtml = `
                <input type="file" id="fileInput" accept=".pdf,.doc,.docx" required>
                <p class="error-msg" style="color: red; display: none;">Please select a document.</p>
            `;

            Swal.fire({
                title: 'Received Confirmation',
                html: `${fileUploadHtml}<p>Are you sure you have received this bill?</p>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Received',
                cancelButtonText: 'Cancel',
                customClass: {
                    popup: 'Received-popup',
                    title: 'Received-title',
                    text: 'Received-text',
                    confirmButton: 'Received-confirm-button',
                    cancelButton: 'Received-cancel-button'
                },
                preConfirm: () => {
                    const errorMsg = document.querySelector('.error-msg');
                    if (!fileInput.files.length) {
                        errorMsg.style.display = 'block';
                        return false; // Prevent closing the modal
                    }
                    errorMsg.style.display = 'none';
                    return true; // Close the modal
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData();
                    formData.append('file', fileInput.files[0]); // Append the selected file to the FormData
                    formData.append('id', id)


                    axios.post('uploadBillDoc/', formData) // Replace 'UploadEndpoint' with the actual upload URL
                        .then((response) => {
                            axios.get('Received/' + id)
                                .then((response) => {
                                    Toast.fire({
                                        icon: 'success',
                                        title: 'Update Successfully'
                                    });
                                    this.$router.push('/list_bill');
                                    this.BillListDetails();
                                    location.reload();
                                })
                                .catch((error) => {
                                    console.log("Error remove:" + error);
                                });
                        })
                        .catch((error) => {
                            console.log("Error uploading document:" + error);
                        });
                }
            });

            fileInput = document.getElementById('fileInput');
        },

        
        //Function to pick value from id in select2
        changefunction(id){
            if($('#'+id).val()!=""){
                $('#'+id).removeClass('is-invalid select2');
                $('#'+id+'_err').html('');
                $('#'+id).addClass('select2');
            }
            if(id=="status"){
                this.form.status=$('#status').val();
            }
        }
    },
    
    async mounted(){
        this.BillListDetails();
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
        this.dt = $("#BillList").DataTable({
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
        this.dt =  $("#BillList").DataTable();
    },

    watch: {
        BillList() {
            this.dt.destroy();
                this.$nextTick(()=>{
                    this.dt = $("#BillList").DataTable({
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
