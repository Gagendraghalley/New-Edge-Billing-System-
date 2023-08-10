<template>
    <div class="container-fluid">
        <div class="form-group table-responsive col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card card-primary card-outline col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card-body box-profile">
                    <table id="userList" cellspacing="0" width="100%" class="table table-bordered table-striped table-head-fixed">
                    <thead>
                        <tr>
                            <th style="font-weight: bold; font-family: 'Times New Roman'">Sl.No.</th>
                            <th style="font-weight: bold; font-family: 'Times New Roman'">Name</th>
                            <th style="font-weight: bold; font-family: 'Times New Roman'">Email Address</th>
                            <th style="font-weight: bold; font-family: 'Times New Roman'">Contact Number</th>
                            <th style="font-weight: bold; font-family: 'Times New Roman'">Company Name</th>
                            <th style="font-weight: bold; font-family: 'Times New Roman'">Position Held</th>
                            <th style="font-weight: bold; font-family: 'Times New Roman'">System Role</th>
                            <th style="font-weight: bold; font-family: 'Times New Roman'">Completed tasks</th>
                            <th style="font-weight: bold; font-family: 'Times New Roman'">Under Process Tasks</th>
                            <th style="font-weight: bold; font-family: 'Times New Roman'">Pending tasks</th>
                            <th style="font-weight: bold; font-family: 'Times New Roman'">Total tasks</th>
                            <th style="font-weight: bold; font-family: 'Times New Roman'">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(user, index) in userList" :key="index">
                            <td>{{ index + 1 }}</td>
                            <td>{{ user.name }}</td>
                            <td>{{ user.email }}</td>
                            <td>{{ user.phone_number }}</td>
                            <td>{{ user.companyName }}</td>
                            <td>{{ user.position }}</td>
                            <td>{{ user.system_role }}</td>
                            <td><span>{{ user.completed_tasks}}</span></td>
                            <td><span>{{ user.under_process_tasks}}</span></td>
                            <td><span>{{ user.pending_tasks}}</span></td>
                            <td><span class="badge badge-secondary">{{ user.total}} </span></td>
                            <td>
                                <a href="#" class="btn btn-danger btn-sm btn-flat text-white" @click="DeleteUser(user.id)" :disabled="isLoading"><i class="fa fa-trash"></i> Delete</a>
                            </td>
                            <div v-if="isLoading" class="overlay">
                                <i class="fas fa-spinner fa-spin"></i>
                            </div>
                        </tr>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            userList: [],
            dt:'',
            isLoading : false,
            form: new form({
                //
            }),
        };
    },
    methods: {
      //Loading all system user with number of tasks
        getAllSystemUsers() {
            this.isLoading = true;
            this.userList = []
            axios.get('getAllSystemUsers')
            .then((response) => {
                let data = response.data;
                if(data){
                    this.userList= data;
                }
                this.isLoading = false;
            });
        },
        
        //making user inactive
        DeleteUser(id){
            Swal.fire({
                title: 'Delete Confirmation',
                text: 'Are your sure you want to delete this user from the system?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Delete',
                cancelButtonText: 'Cancel',
                customClass: {
                popup: 'Delete-popup',
                title: 'Delete-title',
                text: 'Delete-text',
                confirmButton: 'Delete-confirm-button',
                cancelButton: 'Delete-cancel-button'
                }
            }).then((result) => {
                this.isLoading = true;
                if(result.isConfirmed){
                    axios.get('DeleteUser/'+id)
                    .then((response) => {
                        let status = response.data;
                        if(status=='taskExist'){
                                Swal.fire({
                                text: 'Sorry! You cannot delete this user since this user got some pending or under process work to be complete in the system',
                                icon: 'info',
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Okay',
                            });
                        }
                        else{
                            Toast.fire({
                            icon: 'success',
                            title: 'User Deleted Successfully'
                            })
                            this.$router.push('/list_users')
                            this.getAllSystemUsers();
                        }
                    })
                    .catch((error) => {
                        console.log("Error remove:"+error);
                    })
                .finally(() => {
                    this.isLoading = false;
                });
                }
            });
        },
    },
    mounted() {
        this.getAllSystemUsers();
        this.dt = $("#userList").DataTable({
            "responsive": false,
            "autoWidth": true,
            scrollY:        "300px",
            scrollX:        true,
            scrollCollapse: true,
            paging:         false,
            forceHide: true,
            searching: true,
            fixedColumns:   {
                leftColumns: 2
            },
        });
        this.dt =  $("#userList").DataTable();
    },
    watch: {
        userList() {
        this.dt.destroy();
            this.$nextTick(()=>{
                this.dt = $("#userList").DataTable({
                "responsive": false,
                "autoWidth": true,
                scrollY:        "300px",
                scrollX:        true,
                scrollCollapse: true,
                paging:         false,
                forceHide: true,
                searching: true,
                fixedColumns:   {
                    leftColumns: 2
                },
                })
            });
        },
    },
};

</script>
<style scoped>
th, td { white-space: nowrap; }
    div.dataTables_wrapper {
        width: 900px;
        margin: 0 auto;
    }
    table.DTFC_Cloned thead,table.DTFC_Cloned tfoot{background-color:white}div.DTFC_Blocker{background-color:white}div.DTFC_LeftWrapper table.dataTable,div.DTFC_RightWrapper table.dataTable{margin-bottom:0;z-index:2}div.DTFC_LeftWrapper table.dataTable.no-footer,div.DTFC_RightWrapper table.dataTable.no-footer{border-bottom:none}table.dataTable.display tbody tr.DTFC_NoData{background-color:transparent}div.DTFC_LeftBodyLiner{overflow-x: hidden}
    .underline {
        text-decoration: underline;
    }
    .table-striped tbody tr:nth-of-type(odd) {
        background-color: #f9f9f9;
    }
    .logout-popup {
        border-radius: 8px;
        background-color: #f5f5f5;
        border: none;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        .logout-title {
        font-size: 24px;
        font-weight: bold;
        color: #333;
        }

        .logout-text {
        font-size: 18px;
        color: #555;
        }

        .logout-confirm-button {
        background-color: #3085d6;
        color: #fff;
        padding: 10px 20px;
        border-radius: 5px;
        font-size: 16px;
        }

        .logout-cancel-button {
        background-color: #d33;
        color: #fff;
        padding: 10px 20px;
        border-radius: 5px;
        font-size: 16px;
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
</style>