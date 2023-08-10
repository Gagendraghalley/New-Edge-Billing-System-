<template>
    <div class="form-group table-responsive col-lg-12 col-md-12 col-sm-12 col-xs-12 mt-4 ">
        <table id="orgList" cellspacing="0" width="100%" class="table table-bordered table-striped table-head-fixed" >
            <thead>
                <tr>
                    <th style='font-weight: bold;font-family: "Times New Roman", Times, serif'>Sl.No.</th>
                    <th style='font-weight: bold;font-family: "Times New Roman", Times, serif'>Name</th>
                    <th style='font-weight: bold;font-family: "Times New Roman", Times, serif'>Description</th>
                    <th style='font-weight: bold;font-family: "Times New Roman", Times, serif'>Organization Code</th>
                    <th style='font-weight: bold;font-family: "Times New Roman", Times, serif'>Status</th>
                    <th style='font-weight: bold;font-family: "Times New Roman", Times, serif'>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(org, index) in orgList" :key="index">
                    <td>{{ index + 1 }} </td>
                    <td>{{ org.name }}</td>
                    <td>{{ org.description}} </td>
                    <td>{{ org.org_code}} </td>
                    <td v-if="org.status == 1"><span class="text-info">Active</span></td>
                    <td v-else><span class="text-danger">Inactive</span></td>
                    <td style='font-weight: bold;font-family: "Times New Roman", Times, serif'>
                        <a href="#" class="btn btn-info btn-sm btn-flat text-white" @click="loadeditpage(org)"><i class="fa fa-edit"></i> Edit</a>
                        <a href="#" class="btn btn-danger btn-sm btn-flat text-white" @click="DeleteOrg(org.id)"><i class="fa fa-trash"></i> Delete</a>
                    </td>
                    <div v-if="isLoading" class="overlay">
                        <i class="fas fa-spinner fa-spin"></i>
                    </div>
                </tr>
            </tbody>
        </table>
    </div>
</template>
<script>
export default {
    components: {
    },
    data() {
        return {
            orgList:[],
            dt:'',
            isLoading: false,
            form: new form({
                taskStatus:'',
                priority:'',
            }),
        }
    },
    methods: {
        //Redirect to edit page
        loadeditpage(data){
            this.$router.push({name:"edit_organization",params:{data:data}});
        },

        //Loading all organization list
        orgListDetails(){
            this.isLoading = true;
            this.orgList =[];
            let uri="";
                uri='orgListDetails';
                axios.get(uri)
                .then(response => {
                    let data = response.data;
                    this.orgList = data;
                });
                this.isLoading = false;
        },

        //deleting the organization
        DeleteOrg(id){
            Swal.fire({
                title: 'Delete Confirmation',
                text: 'Are your sure you want to delete this organization from syste,?',
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
                    axios.get('OrgDelete/'+id)
                    .then((response) => {
                        let status = response.data;
                        if(status=='Active_user'){
                            Swal.fire({
                            text: 'Sorry! You cannot delete this organization since system user(s) are mapped to this organization',
                            icon: 'info',
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Okay',
                        });
                        }else{
                            Toast.fire({
                            icon: 'success',
                            title: 'Organization Deleted Successfully'
                            })
                            this.$router.push('/list_organization')
                            this.orgListDetails();
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
    
    async mounted(){
        this.orgListDetails();
        this.dt = $("#orgList").DataTable({
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
        this.dt =  $("#orgList").DataTable();
    },

    watch: {
        orgList() {
            this.dt.destroy();
                this.$nextTick(()=>{
                    this.dt = $("#orgList").DataTable({
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
}
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
