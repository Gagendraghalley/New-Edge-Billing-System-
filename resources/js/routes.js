export default [
    { path: '/change_password', component: require('./components/profile/change_password.vue').default },
    { path: '/',name:'', component: require('./components/Dashboard.vue').default }, 
    { path: '/dashboard', name:'dashboard', component: require('./components/Dashboard.vue').default },
    { path: '/userprofile', component: require('./components/Profile/Userprofile.vue').default },
    { path: '/passReset', component: require('./components/profile/password_reset.vue').default },
    { path: '/PrintDetails',name:'PrintDetails', component: require('./components/invoice_creation/PrintDetails.vue').default },
    { path: '/Printpurchase_order',name:'Printpurchase_order', component: require('./components/purchase_order/Printpurchase_order.vue').default },
    { path: '/PrintMoneyReceipt',name:'PrintMoneyReceipt', component: require('./components/bill_update/PrintMoneyReceipt.vue').default },
    

    //Creating and listing own task
    { path: '/invoice_index',  component: require('./components/invoice_creation/invoice_index.vue').default ,
    children:[
            { path: '/',name:'invoice_index', component: require('./components/invoice_creation/list_invoice.vue').default },
            { path: '/list_invoice',name:'list_invoice', component: require('./components/invoice_creation/list_invoice.vue').default },
            { path: '/create_invoice',name:'create_invoice', component: require('./components/invoice_creation/create_invoice.vue').default },
            { path: '/view_invoice',name:'view_invoice', component: require('./components/invoice_creation/view_invoice.vue').default },
            { path: '/edit_invoice',name:'edit_invoice', component: require('./components/invoice_creation/edit_invoice.vue').default },
        ]
    },

    //Updating Bill
    { path: '/bill_index',  component: require('./components/bill_update/bill_index.vue').default ,
    children:[
            { path: '/',name:'bill_index', component: require('./components/bill_update/list_bill.vue').default },
            { path: '/list_bill',name:'list_bill', component: require('./components/bill_update/list_bill.vue').default },
            { path: '/view_bill',name:'view_bill', component: require('./components/bill_update/view_bill.vue').default },
            { path: '/edit_bill',name:'edit_bill', component: require('./components/bill_update/edit_bill.vue').default },
        ]
    },
    //Creating user by admin
    { path: '/users_index',  component: require('./components/User/users_index.vue').default ,
    children:[
            { path: '/',name:'users_index', component: require('./components/User/list_users.vue').default },
            { path: '/list_users',name:'list_users', component: require('./components/User/list_users.vue').default },
            { path: '/create_users',name:'create_users', component: require('./components/User/create_users.vue').default },
            { path: '/edit_users',name:'edit_users', component: require('./components/User/edit_users.vue').default },
        ]
    },

    //Creating and listing purchase order
    { path: '/purchase_order_index',  component: require('./components/purchase_order/purchase_order_index.vue').default ,
    children:[
            { path: '/',name:'purchase_order_index', component: require('./components/purchase_order/list_purchase_order.vue').default },
            { path: '/list_purchase_order',name:'list_purchase_order', component: require('./components/purchase_order/list_purchase_order.vue').default },
            { path: '/create_purchase_order',name:'create_purchase_order', component: require('./components/purchase_order/create_purchase_order.vue').default },
            { path: '/view_purchase_order',name:'view_purchase_order', component: require('./components/purchase_order/view_purchase_order.vue').default },
            { path: '/edit_purchase_order',name:'edit_purchase_order', component: require('./components/purchase_order/edit_purchase_order.vue').default },
        ]
    },

    //Creating and listing purchase order
    { path: '/update_purchase_order',  component: require('./components/purchase_order_list/purchaseOrderlist_index.vue').default ,
    children:[
            { path: '/',name:'purchase_order_index', component: require('./components/purchase_order_list/list_purchaselist.vue').default },
            { path: '/list_purchaselist',name:'list_purchaselist', component: require('./components/purchase_order_list/list_purchaselist.vue').default },
            { path: '/create_purchaselist',name:'create_purchaselist', component: require('./components/purchase_order_list/create_purchaselist.vue').default },
            // { path: '/view_purchase_order',name:'view_purchase_order', component: require('./components/purchase_order_list/view_purchase_order.vue').default },
            // { path: '/edit_purchase_order',name:'edit_purchase_order', component: require('./components/purchase_order_list/edit_purchase_order.vue').default },
        ]
    },

];
