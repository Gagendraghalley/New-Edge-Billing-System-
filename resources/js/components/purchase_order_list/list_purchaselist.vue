<template>
    <div class="form-group table-responsive col-lg-12 col-md-12 col-sm-12 col-xs-12 mt-4 ">
        <div class="d-flex align-items-center mb-3">
            <button class="btn btn-outline-secondary btn-toggle-form" @click="toggleForm">
                <span v-if="formExpanded">
                    <i class="fas fa-minus text-info"></i>
                </span>
                <span v-else>
                    <i class="fas fa-plus text-info"></i>
                </span>
            </button>
            <h5 class="mb-0 ml-3"><b>Advance Search <i class="fas fa-search text-info"></i></b></h5>
        </div>
        <div v-show="formExpanded">
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
                    <label for="status">Purchase Order Status: <span class="text-danger">*</span></label>
                        <select v-model="form.status" @change="validateStatus" :class="{ 'is-invalid select2 select2-hidden-accessible': form.errors.has('status') }" class="form-control select2" id="status" required>
                            <option value="">-- Select --</option>
                            <option value="Received">Purchase Order Received</option>
                            <option value="Not_Received">Purchase Order Not Received</option>
                            <option value="All">All Purchase Order</option>
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
        </div>
        <table id="PurchaseOrderList" cellspacing="0" width="100%" class="stripe cell-border order-column" border="1" style="border-color: rgb(235, 229, 229);border-collapse: collapse;">
            <thead>
                <tr>
                    <th style='font-weight: bold;font-family: "Times New Roman", Times, serif'>Sl.No.</th>
                    <th style='font-weight: bold;font-family: "Times New Roman", Times, serif'>Reference Number</th>
                    <th style='font-weight: bold;font-family: "Times New Roman", Times, serif'>Total Amount</th>
                    <th style='font-weight: bold;font-family: "Times New Roman", Times, serif'>Address To</th>
                    <th style='font-weight: bold;font-family: "Times New Roman", Times, serif'>End User</th>
                    <th style='font-weight: bold;font-family: "Times New Roman", Times, serif'>Order Date</th>
                    <th style='font-weight: bold;font-family: "Times New Roman", Times, serif'>Recieved Date</th>
                    <th style='font-weight: bold;font-family: "Times New Roman", Times, serif'>Bill Recieved By</th>
                    <th style='font-weight: bold;font-family: "Times New Roman", Times, serif'>Bill Status</th>
                    <th style='font-weight: bold;font-family: "Times New Roman", Times, serif'>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(Purchase, index) in PurchaseOrderList" :key="index">
                    <td>{{ index + 1 }} </td>
                    <td>{{ Purchase.refer_no }} </td>
                    <td>{{ Purchase.unit }} {{ Purchase.totalAmount }} </td>
                    <td>{{ Purchase.addressTo }} </td>
                    <td>{{ Purchase.end_user }} </td>
                    <td>{{ Purchase.order_date }} </td>
                    <td>{{ Purchase.received_date}} </td>
                    <td>{{ Purchase.recieved_by}} </td>
                    <td v-if="Purchase.status == 'Pending'"><span class="text-info">Not Received</span></td>
                    <td v-else><span class="text-success">Received</span></td>

                    <td style="font-weight: bold; font-family: 'Times New Roman', Times, serif">
                        <a v-if="Purchase.status === 'Pending'  && system_role!=='View'" href="#" class="btn btn-info btn-sm btn-flat text-white" @click="Recieved(Purchase.id)"><i class="fa fa-edit"></i> Received</a>
                        <a v-else-if="Purchase.purchase_file" href="#" @click="openFile(Purchase.purchase_file)" download>
                            <i :class="getFileIconClass(Purchase.purchase_file)"> </i> {{ Purchase.refer_no }}
                        </a>
                        <a v-else>
                            No File
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
        props: {
            Purchase: {
            type: Object,
            required: true,
            },
        },
    },
    data() {
        return {
            formExpanded: false,
            PurchaseOrderList:[],
            system_role:'',
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
        toggleForm() {
            this.formExpanded = !this.formExpanded;
        },
        //downloading the
        openFile(filename){
            let file_path=filename;
            file_path=file_path.replaceAll('/', 'SSS');
            let uri = 'viewFiles/'+file_path;
            window.open(uri,"_black");
        },

        //identifing file and displaying icon based on file type
        getFileIconClass(file) {
            const fileExtension = file.split('.').pop().toLowerCase();
            switch (fileExtension) {
                case 'pdf':
                    return 'fa fa-file-pdf'; // Assuming you have a PDF icon class
                case 'xlsx':
                case 'xls':
                    return 'fa fa-file-excel'; // Assuming you have an Excel icon class
                case 'docx':
                case 'doc':
                    return 'fa fa-file-word'; // Assuming you have a Word icon class
                    case 'png':
                case 'jpg':
                case 'jpeg':
                case 'gif':
                case 'svg':
                return 'fa fa-file-image'; // Assuming you have an Image icon class
                default:
                return 'fa fa-file'; // Default icon class for unknown file types
            }
        },                                                                                                      

        Prinpage(data){ 
            this.$router.push({name:"PrintDetails",params:{data:data}});
        },

        //Loading all Purchase list by userId
        PurchaseOrderListDetails(){
            this.PurchaseOrderList =[];
            let uri="";
                uri='PurchaseOrderList';
                axios.get(uri)
                .then(response => {
                    let data = response.data;
                    this.PurchaseOrderList = data;
                })
                .catch((err) => {
                console.log("Error:"+err)
            })
            ;
            
        },

        //filtering by parameter
        Filter() {
            this.PurchaseOrderList = [];
            //Calling methods to validate
            this.validateFromDate();
            this.validateToDate();
            this.validateStatus()

            let params = this.form.status + '__' + this.form.from_date + '__' + this.form.to_date;
            let uri = 'PurchaseOrderListByParams/' + params;
            // Clear any previous data before making the new request
            this.PurchaseOrderList = [];

            axios.get(uri)
                .then(response => {
                    let data = response.data;
                    this.PurchaseOrderList = data;
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

        showErrorMessage(message) {
            const errorMsg = document.querySelector('.error-msg');
            errorMsg.textContent = message;
            errorMsg.style.display = 'block';
        },

        Recieved(id) {
            //not allowed to pick future date
            const today = new Date().toISOString().split("T")[0];  // Get current date in YYYY-MM-DD format
            const inputHtml = `
                <div class="input-group">
                    <label for="dateInput">Received Date: <span class="text-danger">*</span> &nbsp</label>
                    <input type="date" id="dateInput" class="form-control" required max="${today}">
                </div><br>
                <div class="input-group">
                    <label for="fileInput">Attach Document:<span class="text-danger">*</span></label>
                    <input type="file" id="fileInput" class="form-control-file"  required accept=".pdf,.doc,.docx"> 
                </div>
                <p class="error-msg" style="color: red; display: none;"></p>
            `;


            Swal.fire({
                title: 'Received Confirmation',
                html: `
                    <div class="input-container">
                        ${inputHtml}
                    </div><br>
                    <p>Are you sure you have received this purchase order?</p>
                `,
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
                    cancelButton: 'Received-cancel-button',
                    content: 'Received-content'
                },
                preConfirm: () => {
                    const dateInput = document.getElementById('dateInput');
                    const fileInput = document.getElementById('fileInput');
                    const errorMsg = document.querySelector('.error-msg');
                    
                    if (!fileInput.value.trim() || !dateInput.value) {
                        this.showErrorMessage('Please fill out all required fields.');
                        return false; // Prevent closing the modal
                    }
                    
                    errorMsg.style.display = 'none';
                    return true; // Close the modal
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const dateInput = document.getElementById('dateInput');
                    const fileInput = document.getElementById('fileInput');

                    const formData = new FormData();
                    formData.append('file', fileInput.files[0]);
                    formData.append('id', id);
                    formData.append('dateOfRecieved', dateInput.value);

                    axios.post('uploadPurchaseOrderDoc/', formData) // Replace 'UploadEndpoint' with the actual upload URL
                        .then((response) => {
                            if(response){
                                Toast.fire({
                                icon: 'success',
                                title: 'Update Successfully'
                            });
                            this.$router.push('/list_purchaselist');
                            this.PurchaseOrderListDetails();
                            location.reload();
                            }
                        })
                        .catch((error) => {
                            console.log("Error uploading document:" + error);
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
            if(id=="status"){
                this.form.status=$('#status').val();
            }
        }
    },
    
    async mounted(){
        this.PurchaseOrderListDetails();
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
            this.system_role = data.system_role;
        })
            this.dt = $("#PurchaseOrderList").DataTable({
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
        this.dt =  $("#PurchaseOrderList").DataTable();
    },

    watch: {
        PurchaseOrderList() {
            this.dt.destroy();
                this.$nextTick(()=>{
                    this.dt = $("#PurchaseOrderList").DataTable({
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
.input-group {
    margin-bottom: 15px;
}

.input-group label {
    display: block;
    margin-bottom: 5px;
}

.form-control {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    transition: border-color 0.2s;
}

.form-control:focus {
    border-color: #3085d6;
    outline: none;
}

.form-control-file {
    display: block;
    padding: 5px;
}
   .fa {
  /* Your icon styling goes here */
        font-size: 20px; /* Set the desired font size */
        color: #3498db; /* Set the desired color for the icons */
    }
    .fa-file-pdf::before {
    content: '\f1c1'; /* Unicode for PDF icon in Font Awesome */
    }
    .fa-file-excel::before {
    content: '\f1c3'; /* Unicode for Excel icon in Font Awesome */
    }
    .fa-file-word::before {
    content: '\f1c2'; /* Unicode for Word icon in Font Awesome */
    }
    .fa-file::before {
    content: '\f15b'; /* Default file icon in Font Awesome */
    }
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
