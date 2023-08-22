<template>
    <div class="money-receipt">
        <div class="header">
            <div class="logo">
                <img src="images/netlogo.png" alt="Company Logo" class="logo">
            </div>
            <div class="header-content">
                <p class="title">MONEY RECEIPT</p>
                <p class="company-name">NEW EDGE TECHNOLOGIES PVT LTD</p>
                <p class="address">2<sup>nd</sup> Floor, City Mail Building, Thimphu</p>
            </div>
        </div>
    
        <div class="content">
            <!-- Form Content -->
            <form class="receipt-form">
                <div class="row form-group">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <label style="text-align: left;">Receipt No: <span><u>{{ this.Receipt_no }}</u></span></label>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <label style="text-align: right;">Received Date: <span><u>{{ this.received_date }}</u></span></label>
                    </div>
                </div><br>
                <div class="row form-group">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label>Received with thanks from : {{ this.addressTo }}</label>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <label>Bill Amount (Nu): <span>{{ this.totalAmount }}</span></label>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <label>TDS (Nu): <span>{{ this.tds }}</span></label>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <label>Bill Receipt Ref: <span>{{ this.reference_number }}</span></label>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <label>Bill Number: <span>{{ this.Bill_no }}</span></label>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <label>The Sum of Amount Received (Nu): <span>{{ this.amount_received }}</span></label>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <label>Amount Received in words: <span>{{ this.totalAmountInWords }}</span></label>
                    </div>
                </div><br>
                <div class="form-group">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <label>For New Edge (Signature):</label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="signature-container col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <p class="signature-label">Received by: <span>{{ this.received_by }}</span></p>
                        <img v-if="this.E_Signature" class="signature-image" :src="'images/' + this.E_Signature" alt="E-Signature">
                        <img v-else class="signature-image" alt="No E-Signature">
                    </div>
                </div>
                <div class="divider"></div>
                <PrintDetails v-if="printing" :form="form" />
                <div class="form-group row text-right d-print-none">
                    <div class="btn-group ml-auto mr-4 mt-2 mb-0">
                        <button type="button" class="btn btn-outline-primary btn-sm" @click="startPrint">
                            <i class="fas fa-print"></i> Print Money Receipt
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <div class="footer">
            <p class="contact-info">Contact Number: +975-17417536 / +975-17611511 / +975-02-337191 / 337190</p>
            <p class="contact-info">Email Address: info@newedge.bt</p>
        </div>
    </div>
</template>
    
    <script>
    import numberToWords from 'number-to-words';
    export default {
        data(){
            return  {
                item_list: [],
                totalAmount:0,
                addressTo:'',
                printing:false,
                received_by:'',
                totalAmountInWords:'',
                isLoading:'',
                E_Signature:'',
                Receipt_no:'',
            }
        },
        methods:{
            ConvertedIntoWords(amount){
                 // Convert numeric amount to words
                this.totalAmountInWords = numberToWords.toWords(this.amount_received);
                // Remove commas from the words
                this.totalAmountInWords = this.totalAmountInWords.replace(/,/g, '');
    
                // Capitalize the first letter of each word
                const words = this.totalAmountInWords.split(' ');
                const capitalizedWords = words.map(word => {
                    return word.charAt(0).toUpperCase() + word.slice(1);
                });
    
                // Join the capitalized words back into a sentence
                this.totalAmountInWords = capitalizedWords.join(' ');
                
            },
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
            axios.get('getSessionDetail')
            .then((response) => {
                let data = response.data.data;
                this.received_by = data.name;
                if(data.email == 'kinley.om@newedge.bt'){
                    this.E_Signature = 'kinley.png';
                }
                else if(data.email == 'sanjeet.rai@newedge.bt'){
                    this.E_Signature = 'sanjeet.png';
                }
                else if(data.email == 'bhakta.bomjan@newedge.bt'){
                    this.E_Signature = 'ceo.png';
                }
                else if(data.email == 't.wangdi@newedge.bt'){
                    this.E_Signature = 'thinley.png';
                }
                else{
                    // this.E_Signature ="general.png"
                }
            })
            .catch((errors) => {
            console.log(errors);
            });
            // "images/netlogo.png"
    
        },
        created(){
            this.addressTo=this.$route.params.data.addressTo;
            this.entry_date=this.$route.params.data.entry_date;
            this.received_date=this.$route.params.data.received_date;
            this.Bill_no=this.$route.params.data.Bill_no;
            this.amount_received=this.$route.params.data.amount_received;
            this.totalAmount=this.$route.params.data.totalAmount;
            this.reference_number=this.$route.params.data.reference_number;
            this.tds=this.$route.params.data.tds;
            this.Receipt_no=this.$route.params.data.Receipt_no;
            this.ConvertedIntoWords(this.amount_received);
            
        },
    }
    </script>
    
    <style scoped>
    .form-group {
            margin-bottom: 15px;
        }

    .signature-container {
        border: 1px solid #ccc;
        padding: 10px;
        text-align: center;
        position: relative;
    }

    .signature-label {
        font-weight: bold;
        font-size: 18px;
    }

    .signature-image {
        max-width: 80%;
        height: auto;
        margin-top: 10px;
        filter: grayscale(100%);
        background-color: #fff;
    }

    .signature-highlight {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: white;
        opacity: 0.8;
        mix-blend-mode: difference;
    }

    .divider {
        border-top: 1px solid #ccc;
        margin: 20px 0;
    }
    .money-receipt {
        font-family: Arial, sans-serif;
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
        border: 1px solid #ccc;
        background-color: #fff;
    }
    
    .header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
    }
    
    .logo {
        max-width: 150px;
        height: auto;
    }
    
    .header-content {
        flex-grow: 1;
        text-align: center;
    }
    
    .title {
        font-size: 24px;
        font-weight: bold;
        margin: 0;
        color: #333;
    }
    
    .company-name {
        font-size: 18px;
        color: #333;
    }
    
    .address {
        font-size: 14px;
        color: #555;
    }
    
    .content {
        padding: 20px;
        background-color: #f8f9fa;
        border: 1px solid #ccc;
        border-radius: 5px;
    }
    
    .receipt-form label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
        color: #333;
    }
    
    .receipt-form span {
        color: #555;
    }
    
    .button-container {
        text-align: right;
        margin-top: 20px;
    }
    
    .footer {
        margin-top: 20px;
        text-align: center;
        color: #666;
    }
    
    .contact-info {
        margin: 5px 0;
    }
    
    </style>
    