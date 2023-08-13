export default [
    { path: '/chage_password', component: require('./components/profile/change_password.vue').default },
    { path: '/',name:'', component: require('./components/Dashboard.vue').default }, 
    { path: '/dashboard', name:'dashboard', component: require('./components/Dashboard.vue').default },
    { path: '/userprofile', component: require('./components/Profile/Userprofile.vue').default },
    { path: '/passReset', component: require('./components/profile/password_reset.vue').default },
    { path: '/PrintDetails',name:'PrintDetails', component: require('./components/invoice_creation/PrintDetails.vue').default },


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

    //Creating Organization/Department for user by admin
    { path: '/organization_index',  component: require('./components/Organization/organization_index.vue').default ,
    children:[
            { path: '/',name:'organization_index', component: require('./components/Organization/list_organization.vue').default },
            { path: '/list_organization',name:'list_organization', component: require('./components/Organization/list_organization.vue').default },
            { path: '/create_organization',name:'create_organization', component: require('./components/Organization/create_organization.vue').default },
            { path: '/edit_organization',name:'edit_organization', component: require('./components/Organization/edit_organization.vue').default },
        ]
    },

    //Reporting interface
    // { path: '/task_index',  component: require('./components/Task_creation/task_index.vue').default ,
    // children:[
    //         { path: '/',name:'task_index', component: require('./components/Task_creation/list_task.vue').default },
    //         { path: '/list_task',name:'list_task', component: require('./components/Task_creation/list_task.vue').default },
    //         { path: '/assigned_list_task',name:'assigned_list_task', component: require('./components/Task_creation/assigned_list_task.vue').default },
    //         { path: '/create_tasks',name:'create_tasks', component: require('./components/Task_creation/create_tasks.vue').default },
    //         { path: '/edit_task',name:'edit_task', component: require('./components/Task_creation/edit_task.vue').default },
    //     ]
    // },
    //Assigning task
    { path: '/Assign_task_index',  component: require('./components/Task_assigning/Assign_task_index.vue').default ,
    children:[
            { path: '/',name:'Assign_task_index', component: require('./components/Task_assigning/list_assign_task.vue').default },
            { path: '/list_assign_task',name:'list_assign_task', component: require('./components/Task_assigning/list_assign_task.vue').default },
            { path: '/create_assign_tasks',name:'create_assign_tasks', component: require('./components/Task_assigning/create_assign_tasks.vue').default },
            { path: '/edit_assign_task',name:'edit_assign_task', component: require('./components/Task_assigning/edit_assign_task.vue').default },
            { path: '/edit_re_assign_task',name:'edit_re_assign_task', component: require('./components/Task_assigning/edit_re_assign_task.vue').default },
        ]
    },
];
