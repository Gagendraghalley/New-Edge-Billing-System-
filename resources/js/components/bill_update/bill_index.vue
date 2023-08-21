<template>
    <div>
        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
            <div class="card card-primary card-outline">
                <div class="form-group card-header bg-white pt-2 pb-1 text-right index">
                    <label class="fa-pull-left">Bill(s) List</label>
                    <!-- <button type="button" class="btn btn-flat bg-blue btn-sm" @click="loadpage('create_invoice')"><i class="fa fa-plus"></i> Create Invoice</button> -->
                    <button type="button" class="btn btn-flat bg-success btn-sm"  @click="loadpage('list_bill')"><i class="fa fa-list"></i> List</button>
                    <!-- <button type="button" class="btn btn-flat bg-secondary btn-sm"  @click="loadpage('assigned_list_task')"><i class="fa fa-list"></i> Assigned List</button> -->
                </div>
                <div class="form-group">
                    <div class="col-12">
                        <router-view></router-view>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style>
    .index{
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        padding: 10px;
        background-color: #f8f9fa;
    }
</style>
<script>
export default {
    methods:{
        loadpage:function(type){
            this.$router.push({name:type});
        },
    },
    mounted() {
        let routeparam=this.$route.query.data;
        this.sub_mod_id=routeparam;
    },

}
</script>


