<template>
    <div class="report-container">
        <div class="report-card">
            <div class="report-header">
                <div class="logo">
                    <img src="images/netlogo.png" alt="Company Logo" class="logo"><br>
                        TPN No: <span><u>NAC00078</u></span>
                </div>
                <div class="header-content">
                    <p class="report-title"><u>BILL</u></p>
                    <p class="report-title">NEW EDGE TECHNOLOGIES PVT LTD</p>
                    <p class="report-subtitle">2<sup>nd</sup> Floor, City Mail Building, Thimphu</p>
                    <p class="report-subtitle small-subtitle">Post Box No: 1616</p>
                    <label class="text-center">
                        <label>Bill No: <span><u>{{ form.Bill_no }}</u></span></label>
                    </label>
                </div>
                <div class="logo">
                    
                    <label class="logo">
                            A/C No: <span><u>1234566</u></span>
                    </label><br>
                    <label class="logo">
                            <span>Bank of Bhutan</span>
                    </label><br>
                    <label class="logo">
                        <label>Date: <span><u>{{ form.entry_date }}</u></span></label>
                    </label><br>
                </div>
            </div>
        <!-- Form Content -->
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
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            
                        </div>
                    </div>
                    <div class="form-group row" ref="pdfContent">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th style='font-weight: bold;font-family: "Times New Roman", Times, serif'>Sl.No</th>
                                            <th style='font-weight: bold; width: 50%; font-family: "Times New Roman", Times, serif'>Particulars</th>
                                            <th style='font-weight: bold;width: 7%;font-family: "Times New Roman", Times, serif'>Quantity</th>
                                            <th style='font-weight: bold;width: 15%;font-family: "Times New Roman", Times, serif'>Rate</th>
                                            <th style='font-weight: bold;width: 20%;font-family: "Times New Roman", Times, serif'>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(item, index) in form.item_list" :key="index">
                                            <td>{{ index + 1 }} </td>
                                            <td style="width: 50%; height: 100%; overflow-y: auto;">
                                                <div v-text="item.particular"></div>
                                            </td>

                                            <td style="width: 7%">
                                                <div class="form-control" v-text="item.qty"></div>
                                            </td>
                                            <td style="width: 15%">
                                                <div class="form-control" v-text="item.rate"></div>
                                            </td>
                                            <td style="width: 20%">
                                                <div class="form-control" v-text="item.amount"></div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <p class="text-center"><b>Net Amount: </b></p>
                                            </td>
                                            <td colspan="5">
                                                <div class="form-control" v-text="this.form.totalAmount"></div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="5">
                                                <p class="text-center"><b><u>Term & Condition</u></b></p>
                                                <ul>
                                                    <li>Interest @15% p.a will be charge if the payment is not made within in the stipulated time</li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="5">
                                                <p class="text-center"><b><u>Declaration</u> </b></p>
                                                <ul>
                                                    <li>We declare here that all we mentioned are true and correct</li>
                                                </ul>
                                            </td>
                                        </tr>
                                            
                                        <tr>
                                            <td colspan="1">
                                                <p class="text-center"><b>Amount In Words: </b></p>
                                            </td>
                                            <td colspan="5">
                                                <p>Nu:<b> {{ totalAmountInWords }} only</b></p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <p><b>Recieved by:  </b></p>
                                            </td>
                                            <td colspan="4">
                                                <p><b>For New Edge Technologies: </b></p>
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
                        width: 100%;
                        height: 100%;
                        margin: 0;
                        padding: 0;
                        overflow: hidden;
                    }
                    
                    /* Adjust content to fit within a single page */
                    @page {
                        size: A4 portrait; /* Set the desired paper size and orientation */
                        margin: 0; /* Remove any default margins */
                    }

                    /* Hide system footer */
                    @page {
                        size: A4 portrait; /* Set the desired paper size and orientation */
                        margin: 0;
                        mso-footer-space: 0;
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
