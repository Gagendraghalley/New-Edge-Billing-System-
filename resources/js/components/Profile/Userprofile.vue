<template>
    <div class="container-fluid">
        <div class="card" style="max-height: 100%; max-width: 100%;">
            <div class="card-body card-outline box-profile" :style="`background-image: url(${backgroundImage}); background-size: cover; background-repeat: no-repeat opacity: .8; max-height: 100%`">
                <div class="d-flex justify-content-center align-items-center" style="max-height: 100%; max-width: 100%;">
                    <div class="col-md-3">
                        <div class="card card-outline">
                            <div class="card-body box-profile">
                                <div class="text-center">
                                    <img v-if="isprofile" class="img-fluid img-circle round-image" :src="'storage/' + form.profilePicture" alt="Profile" @error="onErrorLoad" style="max-height: 200px; max-width: 200px;border-radius: 50%;"/>
                                    <img v-else class="profile-user-img img-fluid img-circle" src="images/user.png" alt="User profile picture" style="max-height: 200px; max-width: 200px;border-radius: 50%;"/>
                                </div>
                            </div>
                            <div class="form-group" style="height: 100px;">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                    <label for="profilePicture" class="mb-2">Edit Profile</label>
                                    <input type="file" class="form-control" @change="onChangeFileUpload" id="profilePicture" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 align-items-center">
                <div class="login-logo bg-primary text-white">
                    <b>Personal Details</b>
                </div>
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                        <div>
                            <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item">
                                <label>User Name:</label>
                                <input type="text" class="form-control" @change="removeerror('name','name_err')" v-model="form.name" />
                                <has-error :form="form" field="name"></has-error>
                            </li>
                            <li class="list-group-item">
                                <label>Email Address:</label>
                                <input type="text" class="form-control" @change="removeerror('email','email_err')" v-model="form.email" disabled />
                                <has-error :form="form" field="email"></has-error>
                            </li>
                            <li class="list-group-item">
                                <label>Contact Number:</label>
                                <input type="number" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength = "8" minlength = "8" class="form-control" @change="remove_error('contact_no')" :class="{ 'is-invalid': form.errors.has('contact_no') }" id="contact_no" v-model="form.contact_no" placeholder="Contact No">
                                <has-error :form="form" field="contact_no"></has-error>
                            </li>
                            <!-- organization -->
                            <li class="list-group-item">
                                <label>Select Company:</label>
                                <select v-model="form.org_id" :class="{ 'is-invalid select2 select2-hidden-accessible': form.errors.has('org_id') }" class="form-control select2" name="org_id" id="org_id">
                                    <option v-for="(item, index) in orgList" :key="index" v-bind:value="item.id">{{ item.name}}</option>
                                </select>
                            </li>
                            <li class="list-group-item">
                                <label>Position Held:</label>
                                <input type="text" class="form-control" @change="removeerror('position','position_err')" v-model="form.position" />
                                <has-error :form="form" field="position"></has-error>
                            </li>
                            </ul>
                        </div>
                        <div class="col-md-12 text-right">
                            <button type="button" class="btn btn-primary btn-sm" @click="UpdatePerDetails()" :disabled="isLoading">
                                <i class="fa fa-edit"></i> Update
                            </button>
                            <div v-if="isLoading" class="overlay">
                                <i class="fas fa-spinner fa-spin"></i>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group table-responsive col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="login-logo bg-primary text-white">
                    <b>Task Details</b>
                </div>
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                        <table id="taskList" cellspacing="0" width="100%" class="table table-bordered table-striped table-head-fixed">
                            <thead>
                            <tr>
                                <th style="font-weight: bold; font-family: 'Times New Roman'">Sl.No.</th>
                                <th style="font-weight: bold; font-family: 'Times New Roman'">Title</th>
                                <th style="font-weight: bold; font-family: 'Times New Roman'">Priority Level</th>
                                <th style="font-weight: bold; font-family: 'Times New Roman'">Task Status</th>
                            </tr>
                            </thead>
                            <tbody v-if="userDetails.taskDetails">
                            <tr v-for="(task, index) in userDetails.taskDetails" :key="index">
                                <td>{{ index + 1 }}</td>
                                <td>{{ task.title }}</td>
                                <td v-if="task.priority=='High'"><span class="badge badge-danger">{{ task.priority }}</span></td>
                                <td v-else-if="task.priority=='Medium'"><span class="badge badge-warning">{{ task.priority }}</span></td>
                                <td v-else><span class="badge badge-info">{{ task.priority }}</span></td>
                                <td v-if="task.taskStatus=='Completed'"><span class="badge badge-success">{{ task.taskStatus }}</span></td>
                                <td v-else-if="task.taskStatus=='Under Process'"><span class="badge badge-info">{{ task.taskStatus }}</span></td>
                                <td v-else><span class="badge badge-warning">{{ task.taskStatus }}</span></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import axios from 'axios';
export default {
    data() {
        return {
            orgList:[],
            userDetails:[],
            taskDetails:[],
            isprofile: false,
            profilePicture: '',
            isLoading: false,
            backgroundImage: 'images/background.jpg',
            form: new form({
                name: '',
                email: '',
                contact_no: '',
                position: '',
                profilePicture: '',
                org_id:'',
                imageNew:'',
            }),
    };
},
    methods: {
        onChangeFileUpload(e) {
            //added parameter for identifying 
            this.form.profilePicture = e.target.files[0];
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = () => {
                this.profilePicture = reader.result;
            };
            reader.readAsDataURL(file);
            }
            this.form.imageNew = true;
        },
        onErrorLoad(event) {
            event.target.src = this.profilePicture;
        },
        //updating the details you have eneter in the system
        UpdatePerDetails() {
            this.isLoading = true;
            let formData = new FormData();
            formData.append('name', this.form.name);
            formData.append('email', this.form.email);
            formData.append('contact_no', this.form.contact_no);
            formData.append('imageNew', this.form.imageNew);
            formData.append('position', this.form.position);
            formData.append('org_id', this.form.org_id);
            formData.append('profilePicture', this.form.profilePicture);
            axios.post('UpdatePerDetails', formData)
                .then(() => {
                Toast.fire({
                    icon: 'success',
                    title: 'Details updated Successfully',
                });
                })
                 // Reload the current page
                location.reload()
                .catch((err) => {
                console.log('Error:' + err);
                })
            .finally(() => {
                this.isLoading = false;
                });
        },

        async getPersonalDetails(id) {
            this.isLoading = true;
            axios.get('getPersonalDetails/' + id)
            .then((response) => {
                let data = response.data;
                this.userDetails = data;
                //preparing data for displaying
                this.form.org_id = data.org_id;
                this.form.name = data.name;
                this.form.email = data.email;
                this.form.contact_no = data.phone_number;
                this.form.position = data.position;
                this.form.profilePicture = data.profile_path;
                if (data.profile_path != '') {
                    this.isprofile = true;
                }
                this.isLoading = false;
            });
        },
          //Function to pick value from id in select2
        changefunction(id){
            if($('#'+id).val()!=""){
                $('#'+id).removeClass('is-invalid select2');
                $('#'+id+'_err').html('');
                $('#'+id).addClass('select2');
            }
            if(id=="org_id"){
                this.form.org_id=$('#org_id').val();
            }
        },
           //pulling organization List
        LoadingOrgList(){
            let uri="";
                uri='orgListDetails';
                axios.get(uri)
                .then(response => {
                    let data = response.data;
                    this.orgList = data;
                });
        },
    },

    async mounted() {
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
            this.getPersonalDetails(data.id);
            this.LoadingOrgList();
        })
        .catch((errors) => {
        console.log(errors);
        });
    },
};
</script>
<style>
    .progress {
    height: 25px;
    }

    .progress-bar {
    height: 100%;
    transition: width 0.3s;
    }
    .round-image {
        border-radius: 50%;
        overflow: hidden;
    }

    .round-image img {
        object-fit: cover;
        width: 100%;
        height: 100%;
    }

    .login-logo {
        border-radius: 20px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        padding: 2px;
        background-color: #f8f9fa;
        font-size: 18px;
        text-shadow: 0 0 10px rgba(255, 255, 255, 0.7);
        animation: glow 1.5s ease-in-out infinite alternate;
    }

    .card {
        border-radius: 10px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }

    .form-control {
        border-radius: 5px;
        box-shadow: none;
        border-color: #ddd;
    }

    .table {
        border: 1px solid #ddd;
    }

    .table th,
    .table td {
        font-weight: normal;
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: #f9f9f9;
    }

    .badge {
        font-size: 14px;
        padding: 5px 10px;
    }

    .badge-danger {
        background-color: #dc3545;
    }

    .badge-warning {
        background-color: #ffc107;
    }

    .badge-info {
        background-color: #17a2b8;
    }

    .badge-success {
        background-color: #28a745;
    }

    .card-body {
        background: linear-gradient(45deg, #f6f8f8, #f1f3f3);
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