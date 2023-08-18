<template>
    <div class="report-container">
        <div class="report-card">
            <div class="report-header">
                <div class="header-content">
                    <img src="images/NET.png" alt="Company Logo">
                    <div class="address">
                        <p class="small-subtitle"><b>2<sup>nd</sup> Floor, City Mail, P.B Box 1616, Building, Thimphu Bhutan</b></p>
                    </div>
                    <hr>
                    <div class="reference">
                        <label>Refer No: <span><u>{{ form.refer_no }}</u></span></label>
                    </div>
                    <div class="reference_date">
                        <label>
                            <span>Date: <u>{{ form.order_date }}</u></span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="text-center">
                <label style="font-size: 20;"><u>Purchase Order</u></label>
                
            </div>
            <div class="form-group row" ref="pdfContent">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style='font-weight: bold; width: 30%; font-family: "Times New Roman", Times, serif'>To Address</th>
                                    <th style='font-weight: bold;width: 30%;font-family: "Times New Roman", Times, serif'>Bill And Ship To</th>
                                    <th style='font-weight: bold;width: 30%;font-family: "Times New Roman", Times, serif'>End User Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="width: 30%; height: 100%; overflow-y: auto;">
                                        <div v-html="formatAddress(form.addressTo)"></div>
                                    </td>


                                    <td style="width: 30%;height: 100%; overflow-y: auto;">
                                        <div>
                                            <p>New Edge Technologies Pvt Ltd,<br>2 nd Floor, City Mail, Chubachu,<br>Thimphu Bhutan</p>    
                                        </div>
                                    </td>
                                    <td style="width: 30%; height: 100%; overflow-y: auto;">
                                        <div v-html="formatAddress(form.end_user)"></div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <!-- Form Content -->
            <div class="report-content">
                <form class="change-password-form">
                    <div class="form-group row" ref="pdfContent">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th style='font-weight: bold;font-family: "Times New Roman", Times, serif'>Sl.No</th>
                                            <th style='font-weight: bold; width: 20%; font-family: "Times New Roman", Times, serif'>Part Number</th>
                                            <th style='font-weight: bold; width: 40%; font-family: "Times New Roman", Times, serif'>Description</th>
                                            <th style='font-weight: bold; width: 10%;font-family: "Times New Roman", Times, serif'>Service Duration (Months)</th>
                                            <th style='font-weight: bold; width: 10%;font-family: "Times New Roman", Times, serif'>Quantity</th>
                                            <th style='font-weight: bold; width: 10%;font-family: "Times New Roman", Times, serif'>Rate</th>
                                            <th style='font-weight: bold; width: 10%;font-family: "Times New Roman", Times, serif'>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(item, index) in form.item_list" :key="index">
                                            <td>{{ index + 1 }} </td>
                                            <td style="width: 20%; height: 100%; overflow-y: auto;">
                                                <div v-text="item.part_number"></div>
                                            </td>
                                            <td style="width: 40%; height: 100%; overflow-y: auto;">
                                                <div v-text="item.description"></div>
                                            </td>
                                            <td style="width: 10%; height: 100%; overflow-y: auto;">
                                                <div v-text="item.duration"></div>
                                            </td>
                                            <td style="width: 10%; height: 100%; overflow-y: auto;">
                                                <div v-text="item.qty"></div>
                                            </td>
                                            <td style="width: 10%; height: 100%; overflow-y: auto;">
                                                <div v-text="item.rate"></div>
                                            </td>
                                            <td style="width: 10%; height: 100%; overflow-y: auto;">
                                                <div v-text="item.amount"></div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" style="vertical-align: middle; text-align: center;">
                                                <div class="form-group">
                                                    <p>Total Amount ({{ this.unit }})</p>
                                                </div>
                                            </td>
                                            <td colspan="5" style="vertical-align: left; text-align: center;">
                                                <div class="form-group">
                                                    <p class="text-"> {{ this.totalAmount }}</p>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <div class="form-group">
                                                    <p class="text-center">Amount In words ({{ this.unit }})</p>
                                                </div>
                                            </td>
                                            <td colspan="5">
                                                <div class="form-group">
                                                    <p> {{ this.totalAmountInWords }} Only </p>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            <!-- Term and condition -->
                                <div class="col-lg-10 col-md-12 col-sm-12 col-xs-12">
                                    <label><i>Terms & Conditions</i>:<span class="text-danger">*</span></label>
                                    <div style="font-style: italic" v-html="formatAddress(form.termAndCondition)"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <label>&nbsp;&nbsp;&nbsp;For; New Edge Technologies Pvt Ltd</label>
                    <br><br><br><br><br><br><br> <hr>
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
                    order_date:'',
                    refer_no:'',
                    workorderNo:'',
                    termAndCondition:'',
                    addressTo:'',
                    end_user:'',
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
        //Formating the contain
        formatAddress(address) {
            return address.replace(/,/g, '<br>');
        },
        startPrint() {
            this.printing = true;
            // Apply CSS for printing
            const style = document.createElement('style');
            style.innerHTML = `
                @media print {
                    body {
                        max-width: 100%;
                        max-height: 100%;
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
        Printing_pruchase(id){
            this.form.item_list=[];
            this.Printing_pruchase =[];
            let uri="";
                uri='PurchaseDetails/'+id;
                axios.get(uri)
                .then(response => {
                    let data = response.data;
                    for(let i=0; i<data.length;i++){
                        this.form.item_list.push({
                            description:data[i].description,
                            qty:data[i].qty,
                            rate:data[i].rate,
                            part_number:data[i].part_number,
                            duration:data[i].duration,
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
        this.form.order_date=this.$route.params.data.order_date;
        this.form.termAndCondition=this.$route.params.data.termAndCondition;
        this.form.end_user=this.$route.params.data.end_user;
        this.totalAmount=this.$route.params.data.totalAmount;
        this.totalAmountInWords=this.$route.params.data.totalAmountInWords;
        this.unit=this.$route.params.data.unit;
        this.form.refer_no=this.$route.params.data.refer_no;
        this.Printing_pruchase(this.form.id);
    },
}
</script>
<style>
    .reference {
        float: left;
        display: flex;
        justify-content: flex-start;
        /* Additional styling */
    }
    .reference_date {
        float: right;
        display: flex;
        justify-content: flex-end;
        /* Additional styling */
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
