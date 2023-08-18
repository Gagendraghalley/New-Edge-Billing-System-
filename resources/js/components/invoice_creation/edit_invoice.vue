<template>
    <div class="container-fluid">
        <div class="card card-primary card-outline" id="newworkflow">
            <div class="card-body">
                <div class="bootbox-body">
                    <form class="change-password-form">
                        <div class="row form-group">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label for="entry_date">Entery Date:<span class="text-danger">*</span></label>
                                <input type="date" v-model="form.entry_date" id="entry_date" class="form-control custom-input" @change="remove_error('entry_date')" :class="{ 'is-invalid': form.errors.has('entry_date') }" name="entry_date">
                                <has-error :form="form" field="entry_date"></has-error>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label for="due_date">Due Date:<span class="text-danger">*</span></label>
                                <input type="date" v-model="form.due_date" id="due_date" class="form-control custom-input" @change="remove_error('due_date')" :class="{ 'is-invalid': form.errors.has('due_date') }" name="due_date">
                                <has-error :form="form" field="due_date"></has-error>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Work Order No:<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" rows="3" @change="remove_error('workorderNo')" :class="{ 'is-invalid': form.errors.has('workorderNo') }" id="workorderNo" v-model="form.workorderNo" placeholder="Work Order No"/>
                                <has-error :form="form" field="workorderNo"></has-error>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label>Address To:<span class="text-danger">*</span></label>
                                    <textarea class="form-control" v-model="form.addressTo" :class="{ 'is-invalid': form.errors.has('addressTo')}" id="addressTo"  @change="remove_error('addressTo')" placeholder="Address To"></textarea>
                                <has-error :form="form" field="addressTo"></has-error>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-12">
                                <label>Details:</label>
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
                                                <td style="width: 50%">
                                                    <textarea name="text" class="form-control" v-model="item.particular"></textarea>
                                                </td>
                                                <td style="width: 7%;vertical-align: middle; text-align: center;">
                                                    <input type="number" name="qty" class="form-control" v-model="item.qty" @change="updateAmount(index)">
                                                </td>
                                                <td style="width: 15%;vertical-align: middle; text-align: center;">
                                                    <input type="number" name="rate" class="form-control" v-model="item.rate" @change="updateAmount(index)">
                                                </td>
                                                <td style="width: 20%;vertical-align: middle; text-align: center;">
                                                    <input type="number" name="amount" class="form-control" v-model="item.amount" readonly>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4">
                                                    <p class="text-center"><b>Total: </b></p>
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
                                                    <span>{{ this.form.totalAmountInWords }} only </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="7">
                                                    <div class="button-container">
                                                        <button type="button" class="btn btn-primary btn-sm add-button" id="addMore" @click="addMore()">
                                                            <i class="fa fa-plus"></i> Add More
                                                        </button>
                                                        <button type="button" class="btn btn-danger btn-sm remove-button" id="remove" @click="remove()">
                                                            <i class="fa fa-trash"></i> Remove
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <hr>
                    </form>
                </div>
            </div>
            <div class="card-footer">
                <div class="row form-group fa-pull-right">
                    <div class="col-md-12">
                        <button type="button" @click="restForm()" class="btn btn-flat btn-danger"><i class="fa fa-redo"></i> Reset</button>
                        <button type="button" class="btn btn-flat btn-primary" @click="SaveDetails()">
                            <i class="fa fa-save"></i> Update Invoice
                        </button>
                        <div v-if="isLoading" class="overlay">
                            <i class="fas fa-spinner fa-spin"></i>
                        </div>
                    </div>
                </div>
            </div>
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
            totalAmountInWords:'zero',
            isLoading:'',
            form: new form({
                entry_date:'',
                due_date:'',
                workorderNo:'',
                addressTo:'',
                totalAmountInWords:'',
                totalAmount:'',
                action :'edit',
                
                item_list:
                [{
                    particular:'',qty:0,rate:0, amount:0,total:''
                }],
            })
        }
    },
    methods:{
        updateAmount(index){
            const item = this.form.item_list[index];
            const qty = parseFloat(item.qty || 0);
            const rate = parseFloat(item.rate || 0);
            item.amount = (qty * rate).toFixed(2);
            this.calculateTotalAmount();
        },

        //calculating total amout
        calculateTotalAmount() {
            this.totalAmount = 0; // Initialize totalAmount
            this.totalAmount = this.form.item_list.reduce((total, item) => {
                return total + parseFloat(item.amount || 0);
            }, 0);
            this.form.totalAmount = this.totalAmount;
            this.convertAmountToWords();

        },
        // converting amount into words
        convertAmountToWords() {
            // Convert numeric amount to words
            this.totalAmountInWords = numberToWords.toWords(this.totalAmount);

            // Remove commas from the words
            this.totalAmountInWords = this.totalAmountInWords.replace(/,/g, '');

            // Capitalize the first letter of each word
            const words = this.totalAmountInWords.split(' ');
            const capitalizedWords = words.map(word => {
                return word.charAt(0).toUpperCase() + word.slice(1);
            });

            // Join the capitalized words back into a sentence
            this.totalAmountInWords = capitalizedWords.join(' ');

            // Set the formatted value in your form
            this.form.totalAmountInWords = this.totalAmountInWords;
        },

        //reseting information
        restForm(){
            let formReset =this.form.item_list;
            formReset.splice(0, formReset.length);
            this.form.item_list.push({particular:'',qty:0,rate:0, amount:0,total:0});
            this.form.totalAmount=0;
            this.form.totalAmountInWords='zero';
        },
        //adding Info
        addMore: function(){
            this.count++;
            this.form.item_list.push({
                particular:'',qty:0,rate:0, amount:0})
        },
        /**
         * method to remove fields
         */
        remove(index) {
            if (this.form.item_list.length > 1) {
                const removedItem = this.form.item_list.splice(index, 1)[0];
                const removedAmount = parseFloat(removedItem.amount || 0);
                
                if (removedAmount > this.totalAmount) {
                    this.totalAmount = 0;
                } else {
                    this.totalAmount -= removedAmount;
                }
                
                this.form.totalAmount = this.totalAmount;
                this.convertAmountToWords(); // Recalculate the total amount in words
            }
        },

        SaveDetails(){
            this.isLoading = true;
            this.form.post('create_invoice')
            .then(() => {
                    Toast.fire({
                        icon: 'success',
                        title: 'Details Updated Successfully'
                    })
                    this.$router.push('/list_invoice');
            })
            .catch((err) => {
                console.log("Error:"+err)
            })
            .finally(() => {
                this.isLoading = false;
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
        },
        remove_error(field_id){
            if($('#'+field_id).val()!=""){
                $('#'+field_id).removeClass('is-invalid');
                $('#'+field_id+'_err').html('');
            }
        },
        changefunction(id){
            if($('#'+id).val()!=""){
                $('#'+id).removeClass('is-invalid select2');
                $('#'+id+'_err').html('');
                $('#'+id).addClass('select2');
            }
            if(id=="priority"){
                this.form.priority=$('#priority').val();
            }
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
        this.form.totalAmountInWords=this.$route.params.data.totalAmountInWords;
        this.form.workorderNo=this.$route.params.data.workorderNo;
        this.InvoiceDetails(this.form.id);
    },
}
</script>
<style>
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
    /* Reset some default styles */
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
