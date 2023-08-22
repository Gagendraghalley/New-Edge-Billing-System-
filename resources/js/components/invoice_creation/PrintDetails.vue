<template>
    <div class="report-container">
        <div class="report-card">
            <div class="report-header">
                <div class="logo">
                    <img src="images/netlogo.png" alt="Company Logo" class="logo"><br>
                    <br>
                </div>
                <div class="header-content">
                    <p class="report-title"><u>BILL</u></p>
                    <p class="report-title">NEW EDGE TECHNOLOGIES PVT LTD</p>
                    <p class="report-subtitle">2<sup>nd</sup> Floor, City Mail Building, Thimphu</p>
                    <p class="report-subtitle small-subtitle">Post Box No: 1616</p>
                    <label class="text-center">
                        TPN No: <span><u>NAC00078</u></span>
                    </label>
                </div>
            </div>
        <!-- Form Content -->
            <div class="row form-group">
                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                    <label>Bill No: <span><u>{{ form.Bill_no }}</u></span></label>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <label>Date: <span><u>{{ form.entry_date }}</u></span></label>
                    <label>Due Date: <span><u>{{ form.due_date }}</u></span></label>
                </div>
            </div>
            <hr>
            <div class="report-content">
                <form class="change-password-form">
                    <div class="row form-group">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label>Address To: <span>{{ this.form.addressTo }}</span></label>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Work Order No: <span>{{ this.form.workorderNo }}</span></label>
                        </div>
                    </div>
                    <div class="form-group row" ref="pdfContent">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th style='font-weight: bold; width: 10%;font-family: "Times New Roman", Times, serif'>Sl.No</th>
                                            <th style='font-weight: bold; width: 50%; font-family: "Times New Roman", Times, serif'>Particulars</th>
                                            <th style='font-weight: bold;width: 10%;font-family: "Times New Roman", Times, serif'>Quantity</th>
                                            <th style='font-weight: bold;width: 15%;font-family: "Times New Roman", Times, serif'>Rate</th>
                                            <th style='font-weight: bold;width: 15%;font-family: "Times New Roman", Times, serif'>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(item, index) in form.item_list" :key="index">
                                            <td style="width: 10%; height: 100%; overflow-y: auto;">{{ index + 1 }} </td>
                                            <td style="width: 50%; height: 100%; overflow-y: auto;">
                                                <div v-text="item.particular"></div>
                                            </td>

                                            <td style="width: 10%">
                                                <div class="form-control" v-text="item.qty"></div>
                                            </td>
                                            <td style="width: 15%">
                                                <div class="form-control" v-text="item.rate"></div>
                                            </td>
                                            <td style="width: 15%">
                                                <div class="form-control" v-text="item.amount"></div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <p class="text-center">Net Amount(Nu)</p>
                                            </td>
                                            <td colspan="4">
                                                <div class="form-control" v-text="this.form.totalAmount"></div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <div class="form-group">
                                                    <p class="text-center">Amount In words (Ngultrum ) </p>
                                                </div>
                                            </td>
                                            <td colspan="5">
                                                <div class="form-group">
                                                    <p> {{ this.totalAmountInWords }} Only </p>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="5">
                                                <div class="bank-details">
                                                    <p class="bank-title"><u>Bank Details</u></p>
                                                    <ul class="bank-list">
                                                        <li>
                                                            <span class="bank-name">Beneficiary Name:</span>
                                                            New Edge Technology Private Limited
                                                        </li>
                                                    </ul>
                                                    <table class="bank-table">
                                                        <tr>
                                                            <th>Bank Name</th>
                                                            <th>Bank Account</th>
                                                        </tr>
                                                        <tr>
                                                            <td>Bank of Bhutan</td>
                                                            <td>100901568</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Bhutan National Bank</td>
                                                            <td>0000053343027</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Bhutan Development Bank</td>
                                                            <td>201800025601</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Druk Punjap National Bank</td>
                                                            <td>990210000556</td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td colspan="5">
                                                <p class="text-center"><b><u>Declaration</u> </b></p>
                                                <ul style="font-style: italic">
                                                    <li>This is system generated Bill and  all the bill information provided are true and correct.</li>
                                                    <li>Bills oustanding over the due date are subject to a 1% per day late payment penalty.</li>
                                                </ul>
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <td colspan="2" style="width: 40px">
                                                <p><b>Recieved by:  </b></p> <br><br><br>
                                            </td>
                                            <td colspan="4" style="width: 40px">
                                                <p><b>New Edge Technologies: </b></p><br><br><br>
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
            <div class="report-footer">
                <p class="contact-info">Contact Number: +975-17417536 / +975-17611511 / +975-02-337191 / 337190</p>
                <p class="contact-info">Email Address: info@newedge.bt</p>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data(){
        return  {
            item_list: [],
            totalAmount:0,
            printing:false,
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
            // Apply CSS for printing
            const style = document.createElement('style');
            style.innerHTML = `
                @media print {
                    body {
                        width: 100%; /* Set body width to 100% */
                        height: 100%; /* Set body height to 100% */
                        margin: 0;
                        padding: 0;
                        overflow: hidden;
                    }
                    
                    /* Adjust content to fit within a single page */
                    @page {
                        size: A4 portrait; /* Set the desired paper size and orientation */
                        margin: 0; /* Remove any default margins */
                        mso-footer-space: 0; /* Hide system footer for IE */
                        footer: _blank; /* Hide system footer for other browsers */
                    }

                    /* Adjust table layout for printing */
                    table {
                        table-layout: fixed;
                    }
                    
                    /* Customize your report container for printing */
                    .report-container {
                        width: 100%; /* Make sure report container covers the width of the page */
                        height: 100%; /* Make sure report container covers the height of the page */
                    }
                    
                    /* Add more styles to adjust the content as needed */
                }
            `;
            document.head.appendChild(style);

            this.$nextTick(() => {
                window.print();
                this.printing = false;
                // Remove the added style after printing is done
                document.head.removeChild(style);
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
    .bank-table {
            margin: 0 auto;
        }
    .bank-details {
    margin-top: 10px;
    padding: 5px;
    background-color: #f8f9fa;
    border: 1px solid #ccc;
    border-radius: 5px;
    text-align: center;
    }

.bank-title {
    font-size: 15px;
    text-align: center;
    text-decoration: underline;
    margin-bottom: 5px;
    color: #333;
    }

.bank-list {
    list-style-type: none;
    padding-left: 10px;
    font-style:normal;
    }

.bank-list li {
    font-size: 13px;
    margin-bottom: 8px;
    color: #555;
    }

.bank-name {
    font-weight: bold;
    color: #333;
    margin-right: 5px;
    }
.report-header {
        display: flex;
        align-items: center;
        padding: 10px;
        /* border-bottom: 1px solid #ccc; */
        justify-content: center;
    }

    .logo {
        max-width: 130px;
        height: auto;
    }

    .header-content {
        flex-grow: 1;
        margin-left: 20px;
        text-align: center;
    }

    .report-title {
        font-size: 24px;
        font-weight: bold;
        margin: 0;
    }

    .report-subtitle {
        font-size: 14px;
        margin: 5px 0;
    }

    .small-subtitle {
        font-size: 12px;
        margin: 5px 0;
    }

    .text-left {
        text-align: left;
    }

    .text-right {
        text-align: right;
    }

    u {
        text-decoration: underline;
    }

    sup {
        vertical-align: super;
        font-size: 10px;
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
