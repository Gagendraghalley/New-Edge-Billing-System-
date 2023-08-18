<template>
    <div class="container-fluid">
        <div class="card card-primary card-outline" id="newworkflow">
            <div class="card-body">
                <div class="bootbox-body">
                    <form class="change-password-form">
                        <div class="row form-group">
                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <label for="order_date">Order Date:<span class="text-danger">*</span></label>
                                <input type="date" v-model="form.order_date" id="order_date" class="form-control custom-input" @change="remove_error('order_date')" :class="{ 'is-invalid': form.errors.has('order_date') }" name="order_date">
                                <has-error :form="form" field="order_date"></has-error>
                            </div>
                        </div>
                        <div class="row form-group">
                            <!-- address to -->
                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                    <label>Address To:<span class="text-danger">*</span></label>
                                    <textarea class="form-control" v-model="form.addressTo" :class="{ 'is-invalid': form.errors.has('addressTo')}" id="addressTo"  @change="remove_error('addressTo')" placeholder="Address To"></textarea>
                                <has-error :form="form" field="addressTo"></has-error>
                            </div>
                            <!-- End user -->
                            <div class="col-lg-6 col-md-12 col-sm-112 col-xs-12">
                                    <label>End User Details:<span class="text-danger">*</span></label>
                                    <textarea class="form-control" v-model="form.end_user" :class="{ 'is-invalid': form.errors.has('end_user')}" id="end_user"  @change="remove_error('end_user')" placeholder="Address To"></textarea>
                                <has-error :form="form" field="end_user"></has-error>
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
                                                <th style='font-weight: bold; width: 20%; font-family: "Times New Roman", Times, serif'>Part Number</th>
                                                <th style='font-weight: bold; width: 30%; font-family: "Times New Roman", Times, serif'>Description</th>
                                                <th style='font-weight: bold; width: 15%;font-family: "Times New Roman", Times, serif'>Service Duration (Months)</th>
                                                <th style='font-weight: bold; width: 7%;font-family: "Times New Roman", Times, serif'>Quantity</th>
                                                <th style='font-weight: bold; width: 10%;font-family: "Times New Roman", Times, serif'>Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(item, index) in form.item_list" :key="index">
                                                <td>{{ index + 1 }} </td>
                                                <td style="width:20%">
                                                    <textarea name="text" class="form-control" v-model="item.part_number"></textarea>
                                                </td>
                                                <td style="width: 30%">
                                                    <textarea name="text" class="form-control" v-model="item.description"></textarea>
                                                </td>
                                                <td style="width: 15%; vertical-align: middle; text-align: center;" >
                                                    <input type="number" name="duration" class="form-control" v-model="item.duration">
                                                </td>
                                                <td style="width: 5%;vertical-align: middle; text-align: center;">
                                                    <input type="number" name="qty" class="form-control" v-model="item.qty"  @change="updateAmount(index)">
                                                </td>
                                                <td style="width: 7%;vertical-align: middle; text-align: center;">
                                                    <input type="number" name="rate" class="form-control" v-model="item.rate"  @change="updateAmount(index)">
                                                </td>
                                                <td style="width: 20%; vertical-align: middle; text-align: center;">
                                                    <input type="number" name="amount" class="form-control" v-model="item.amount">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" style="vertical-align: middle; text-align: center;">
                                                    <div class="form-group">
                                                        <p><b>Total Amount</b></p>
                                                    </div>
                                                </td>
                                                <td colspan="1">
                                                    <div class="form-group">
                                                        <label for="unit" class="mr-2" >Unit <span class="text-danger">*</span></label>
                                                        <select v-model="form.unit" :class="{ 'is-invalid select2 select2-hidden-accessible': form.errors.has('unit') }" class="form-control select2" name="unit" id="unit">
                                                            <option v-for="(unit, index) in Unit" :key="index" :value="unit">{{ unit }}</option>
                                                        </select>
                                                        <has-error :form="form" field="unit"></has-error>
                                                    </div>
                                                </td>
                                                <td colspan="1" style="vertical-align: middle; text-align: center;">
                                                    <div class="form-group">
                                                        <p class="text-center"><b>{{ this.form.unit }} </b></p>
                                                    </div>
                                                </td>
                                                <td colspan="5" style="vertical-align: middle; text-align: left;">
                                                    <div class="form-group">
                                                        <p class="text-center"><b>{{ this.form.totalAmount }} </b></p>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <div class="form-group">
                                                        <p class="text-center"><b>{{ this.form.unit }} </b></p>
                                                    </div>
                                                </td>
                                                <td colspan="5">
                                                    <div class="form-group">
                                                        <p><b>{{ this.form.totalAmountInWords }} Only </b></p>
                                                    </div>
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
                        <div class="row form-group">
                            <!-- Term and condition -->
                            <div class="col-lg-10 col-md-12 col-sm-12 col-xs-12">
                                    <label><i>Terms & Conditions</i>:<span class="text-danger">*</span></label>
                                    <textarea class="form-control" v-model="form.termAndCondition" :class="{ 'is-invalid': form.errors.has('termAndCondition')}" id="termAndCondition"  @change="remove_error('termAndCondition')" placeholder="Terms & conditions"></textarea>
                                <has-error :form="form" field="termAndCondition"></has-error>
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
                            <i class="fa fa-save"></i> Update Purchase Order
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
            totalAmount:'0',
            Unit:['BTN','USD','INR'],
            totalAmountInWords:'Zero',
            isLoading:'',
            form: new form({
                order_date:'',
                termAndCondition:'',
                end_user:'',
                unit:'',
                addressTo:'',
                totalAmount:'',
                refer_no:'',
                totalAmountInWords:'',
                action :'edit',
                
                item_list:
                [{
                    part_number:0, description:'',duration:0,qty:0,rate:0,amount:0
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
            this.form.item_list.push({part_number:'', description:'',duration:'',qty:0,rate:0,amount:0});
        },
        //adding Info
        addMore: function(){
            this.count++;
            this.form.item_list.push({
                part_number:'', description:'',duration:'',qty:0, rate:0, amount:0})
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
        PurchaseDetails(id){
            this.form.item_list=[];
            this.PurchaseDetails =[];
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
        },
        SaveDetails(){
            this.isLoading = true;
            this.form.post('create_purposeOrder')
            .then(() => {
                    Toast.fire({
                        icon: 'success',
                        title: 'Details Saved Successfully'
                    })
                    this.$router.push('/list_purchase_order');
            })
            .catch((err) => {
                console.log("Error:"+err)
            })
            .finally(() => {
                this.isLoading = false;
            });
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
            if(id=="unit"){
                this.form.unit=$('#unit').val();
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
        this.form.order_date=this.$route.params.data.order_date;
        this.form.termAndCondition=this.$route.params.data.termAndCondition;
        this.form.end_user=this.$route.params.data.end_user;
        this.form.totalAmount=this.$route.params.data.totalAmount;
        this.form.totalAmountInWords=this.$route.params.data.totalAmountInWords;
        this.form.unit=this.$route.params.data.unit;
        this.form.refer_no=this.$route.params.data.refer_no;
        this.PurchaseDetails(this.form.id);
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
