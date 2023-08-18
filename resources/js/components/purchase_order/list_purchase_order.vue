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
                    <input type="date" v-model="form.from_date" id="from_date" class="form-control" :class="{ 'is-invalid': form.errors.has('from_date') }" name="from_date" required>
                    <has-error :form="form" field="from_date_err"></has-error>
                </div>
                <!-- To Date -->
                <div class="form-group col-md-4">
                    <label for="to_date">To Date:<span class="text-danger">*</span></label>
                    <input type="date" v-model="form.to_date" id="to_date" class="form-control" :class="{ 'is-invalid': form.errors.has('to_date') }" name="to_date" required>
                    <has-error :form="form" field="to_date_err"></has-error>
                </div>
                <!-- Status -->
                <div class="form-group col-md-4">
                    <label for="status">Bill Status: <span class="text-danger">*</span></label>
                        <select v-model="form.status"  :class="{ 'is-invalid select2 select2-hidden-accessible': form.errors.has('status') }" class="form-control select2" id="status" required>
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
        </div>
        <table id="PurchaseList" cellspacing="0" width="100%" class="stripe cell-border order-column" border="1" style="border-color: rgb(235, 229, 229);border-collapse: collapse;" >
            <thead>
                <tr>
                    <th style='font-weight: bold;font-family: "Times New Roman", Times, serif'>Sl.No.</th>
                    <th style='font-weight: bold;font-family: "Times New Roman", Times, serif'>Refers Number</th>
                    <th style='font-weight: bold;font-family: "Times New Roman", Times, serif'>Order Date</th>
                    <th style='font-weight: bold;font-family: "Times New Roman", Times, serif'>Address To</th>
                    <th style='font-weight: bold;font-family: "Times New Roman", Times, serif'>End User</th>
                    <th style='font-weight: bold;font-family: "Times New Roman", Times, serif'>Total Amount</th>
                    <th style='font-weight: bold;font-family: "Times New Roman", Times, serif'>Prepered By</th>
                    <th style='font-weight: bold;font-family: "Times New Roman", Times, serif'>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(purchase, index) in PurchaseList" :key="index">
                    <td>{{ index + 1 }} </td>
                    <td>{{ purchase.refer_no }}</td>
                    <td>{{ purchase.order_date}} </td>
                    <td>{{ purchase.addressTo}} </td>
                    <td>{{ purchase.end_user}} </td>
                    <td>Nu. {{ purchase.totalAmount }} </td>
                    <td>{{ purchase.name}} </td>
                    <td style='font-weight: bold;font-family: "Times New Roman", Times, serif'>
                        <a href="#" class="btn btn-info btn-sm btn-flat text-white" @click="loadeditpage(purchase)"><i class="fa fa-edit"></i> Edit</a>
                        <a  href="#" class="btn btn-danger btn-sm btn-flat text-white" @click="Delete(purchase.id)"><i class="fa fa-trash"></i> Delete</a>
                        <a href="#" class="btn btn-info btn-sm btn-flat text-white" @click="Prinpage(purchase)"><i class="fa fa-print"></i> Print</a>
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
            formExpanded: false,
            system_role:'',
            PurchaseList:[],
            dt:'',
            isLoading: false,
            form: new form({
                purchaseStatus:'',
                priority:'',
            }),
        }
    },
    methods: {
        toggleForm() {
            this.formExpanded = !this.formExpanded;
        },
        //Redirect to edit page
        loadeditpage(data){
            this.$router.push({name:"edit_purchase_order",params:{data:data}});
        },
        Viewpage(data){ 
            this.$router.push({name:"view_purchase_order",params:{data:data}});
        },
        Prinpage(data){ 
            this.$router.push({name:"Printpurchase_order",params:{data:data}});
        },

        //Loading all purchase list by userId
        PurchaseListDetails() {
            this.PurchaseList = [];
            let uri = 'PurchaseList';
            axios.get(uri)
                .then(response => {
                    let data = response.data;
                    this.PurchaseList = data; // Update PurchaseList with the modified data array
                })
                .catch((err) => {
                    console.log("Error: " + err);
                });
        },

        //filtering by parameter
        Filier(){
            this.PurchaseList =[];
            this.form.purchaseStatus='';
            this.form.priority='';
            this.form.purchaseStatus=$('#purchaseStatus').val();
            this.form.priority=$('#priority').val();
            let params =  this.form.purchaseStatus+'__'+this.form.priority;
            let uri="";
                uri='PurchaseListByParams/'+params;
                axios.get(uri)
                .then(response => {
                    let data = response.data;
                    this.PurchaseList = data;
                });
        },
        //deleting the purchase
        Delete(id){
            Swal.fire({
                title: 'Delete Confirmation',
                text: 'Are your sure you want to delete this purchase order?',
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
                    axios.get('DeletePurchase/'+id)
                    .then((response) => {
                        Toast.fire({
                        icon: 'success',
                        title: 'Purchase order Deleted Successfully'
                        })
                        this.$router.push('/list_purchase_order')
                        this.PurchaseListDetails();
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
            if(id=="purchaseStatus"){
                this.form.purchaseStatus=$('#purchaseStatus').val();
            }
            if(id=="priority"){
                this.form.priority=$('#priority').val();
            }
        }
    },
    
    async mounted(){
        this.PurchaseListDetails();
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
        this.dt = $("#PurchaseList").DataTable({
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
        this.dt =  $("#PurchaseList").DataTable();
    },

    watch: {
        PurchaseList() {
            this.dt.destroy();
                this.$nextTick(()=>{
                    this.dt = $("#PurchaseList").DataTable({
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
