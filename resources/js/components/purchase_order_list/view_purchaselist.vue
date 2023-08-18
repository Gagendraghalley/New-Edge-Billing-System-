<template>
    <div class="report-container" ref="pdfContent" >
        <div class="report-card">
        <!-- Header -->
            <div class="report-header">
                <h4 class="report-subtitle"><u>BILL</u></h4>
                <img src="images/netlogo.png" alt="Company Logo" class="logo">
                <div class="header-content">
                    <p class="report-subtitle">2<sup>nd</sup> Floor, City Mail Building, Thimphu</p>
                    <p class="report-subtitle small-subtitle">Post Box No: 1616</p>
                    <label class="text-left">
                        
                        Bill No: <span><u>{{ form.Bill_no }}</u></span>
                    </label>

                    <div style="display: flex; justify-content: space-between;">
                        <label class="text-left">
                            TPN No: <span> <u>NAC00078</u></span>
                        </label>
                        <label class="text-right">
                            Date: <span><u>{{ form.entry_date }}</u></span>
                        </label>
                        
                    </div>
                </div>
            </div>
        <!-- Form Content -->
            <div class="report-content">
                <form class="change-password-form">
                    <div class="row form-group">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label>Address To: <span>{{ form.addressTo }}</span></label>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Work Order No: <span>{{ form.workorderNo }}</span></label>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label for="due_date">Due Date: <span>{{ form.due_date }}</span></label>
                        </div>
                    </div>
                    <div class="form-group row" ref="pdfContent">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th style='font-weight: bold;font-family: "Times New Roman", Times, serif'>Sl.No</th>
                                            <th style='font-weight: bold;font-family: "Times New Roman", Times, serif'>Particulars</th>
                                            <th style='font-weight: bold;font-family: "Times New Roman", Times, serif'>Quantity</th>
                                            <th style='font-weight: bold;font-family: "Times New Roman", Times, serif'>Rate</th>
                                            <th style='font-weight: bold;font-family: "Times New Roman", Times, serif'>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(item, index) in form.item_list" :key="index">
                                            <td>{{ index + 1 }} </td>
                                            <td>
                                                <textarea name="text" class="form-control" v-model="item.particular" readonly></textarea>
                                            </td>
                                            <td>
                                                <input type="number" name="qty" class="form-control" v-model="item.qty" readonly>
                                            </td>
                                            <td>
                                                <input type="number" name="rate" class="form-control" v-model="item.rate" readonly>
                                            </td>
                                            <td>
                                                <input type="number" name="amount" class="form-control" v-model="item.amount" readonly>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="4">
                                                <p class="text-center"><b>Total Amount: </b></p>
                                            </td>
                                            <td colspan="5">
                                                <input type="number" name="amount" class="form-control" v-model="this.form.totalAmount"  readonly/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <p class="text-center"><b>Ngultrum: </b></p>
                                            </td>
                                            <td colspan="5">
                                                <p><b>{{ totalAmountInWords }} only</b></p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3">
                                                <p><b>Recieved by:______________________  </b></p>
                                            </td>
                                            <td colspan="4">
                                                <p><b>For New Edge Technologies:__________________ </b></p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <PrintDetails v-if="printing" :form="form" />
                    <div class="form-group row text-right d-print-none">
                        <div class="btn-group ml-auto mr-4 mt-2 mb-0">
                            <button type="button" class="btn btn-outline-primary btn-sm" @click="startPrint">
                                <i class="fas fa-print"></i> Print Invoice
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        <!-- Footer -->
            <div class="report-footer">
                <p class="contact-info">Contact Number: +975-17417536 / +975-17611511 / +975-02-337191 / 337190</p>
                <p class="contact-info">Email Address: info@newedge.bt</p>
            </div>
        </div>
    </div>
</template>
<script>
// import html2pdf from 'html2pdf.js';
// import axios from 'axios';
export default {
    data(){
        return  {
            item_list: [],
            totalAmount:0,
            totalAmountInWords:'zero',
            isLoading:'',
            form: new form({
                    entry_date:'',
                    due_date:'',
                    workorderNo:'',
                    addressTo:'',
                    Bill_no:'',
                    totalAmountInWords:'',
                    totalAmount:'',
                    action :'add',
                
                item_list:
                [{
                    particular:'',qty:0,rate:0, amount:0,total:''
                }],
            })
        }
    },
    methods:{
        startPrint() {
            this.printing = true;
            this.$nextTick(() => {
                window.print();
                this.printing = false;
            });
        },
        InvoiceDetails(id){
            this.form.item_list=[];
            this.InvoiceDetails =[];
            let uri="";
                uri='InvoiceDetails/'+id;
                axios.get(uri)
                .then(response => {
                    let data = response.data;
                    for(let i=0; i<data.length;i++){
                        this.form.item_list.push({
                            particular:data[i].particular,
                            qty:data[i].qty,
                            rate:data[i].rate,
                            amount:data[i].amount,
                    });
                }
                })
                .catch((err) => {
                console.log("Error:"+err)
            })
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

    },
    created(){
        this.form.addressTo=this.$route.params.data.addressTo;
        this.form.id=this.$route.params.data.id;
        this.form.entry_date=this.$route.params.data.entry_date;
        this.form.due_date=this.$route.params.data.due_date;
        this.form.Bill_no=this.$route.params.data.Bill_no;
        this.form.totalAmount=this.$route.params.data.totalAmount;
        this.totalAmountInWords=this.$route.params.data.totalAmountInWords;
        this.form.workorderNo=this.$route.params.data.workorderNo;
        this.InvoiceDetails(this.form.id);
    },
}
</script>
<style>
#totalAmount {
    font-size: smaller;
    font-family: "bold", sans-serif; /* Replace "Beautiful Font" with the actual font you want to use */
    /* Add any additional styling you desire */
}
.report-container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    background-color: #f5f5f5;
    font-family: Arial, sans-serif;
}

.report-card {
    width: 800px;
    padding: 20px;
    background-color: #ffffff;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.report-header {
    text-align: center;
    margin-bottom: 20px;
}

.report-title {
    font-size: 24px;
    font-weight: bold;
    margin: 0;
}

.report-subtitle {
    font-size: 16px;
    color: #666;
    margin: 5px 0;
}

.report-content {
  /* Add styling for your form content here */
}

.report-footer {
    margin-top: 20px;
    text-align: center;
    color: #666;
}

.contact-info {
  margin: 5px 0;
}
    .bootbox-body{
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        padding: 10px;
        background-color: #f8f9fa;
    }
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
        /* Style the footer container */
        .letter-footer {
            background-color: #f5f5f5;
            padding: 10px;
            border-top: 1px solid #ddd;
            text-align: center;
        }

        /* Style the label within the footer */
        .letter-footer label {
            font-size: 14px;
            color: #555;
            display: block;
        }


</style>
