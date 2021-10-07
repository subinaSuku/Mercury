<template>
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                Edit User
            </h3>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-6 grid-margin stretch-card">
                <div v-if="errors !== null" class="alert alert-danger" style="width:100%;" role="alert">
                    <div class="content-alert__info">
                        <span class="content-alert__info-icon ua-icon-warning"></span>
                    </div>
                    <div class="content-alert__content">
                        <div class="content-alert__heading"></div>
                        <div class="content-alert__message">
                            <ul class="custom-alert__list" v-for="(values , key) in errors" :key="key">
                                <li v-for="(value , index) in values" :key="index">{{ value }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Edit User Form</h4>
                        <form role="form" ref="editUserFrom" name="editUserFrom" v-on:submit.prevent="editUser">                           
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="first_name">First Name</label>
                                    <input type="text" class="form-control" name="first_name" v-model="form.first_name"
                                        placeholder="First Name" required>
                                </div>
                                <div class="form-group">
                                    <label for="last_name">Last Name</label>
                                    <input type="text" class="form-control" name="last_name" v-model="form.last_name"
                                        placeholder="Last Name" required>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" name="email" v-model="form.email"
                                        placeholder="Email address" required>
                                </div>
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input id="password" type="password" name="password" class="form-control"
                                        v-model="form.password" placeholder="password">
                                </div>
                                <div class="form-group">
                                    <label for="is_active">Active Status</label>
                                    <select name="is_active" id="is_active" class="form-control"
                                        v-model="form.is_active">
                                        <option :value="'1'">Active</option>
                                        <option :value="'0'">Inactive</option>
                                        </select>
                                </div>
                                <div class="form-group">
                                    <label for="tags">Role</label>
                                    <select name="role_id" id="role_id" class="form-control"
                                        v-model="form.role_id">
                                        <option value="" disabled>Select user role</option>
                                        <option v-for="role in roles" :key="role.id" :value="role.id">{{ role.name }}</option>
                                        </select>
                                </div>
                            </div>
                            <button type="button" @click="listUser()" class="btn btn-default">Cancel</button>
                            <base-button :class="'btn btn-success float-right'" :type="'submit'"
                             :loading="isLoading" icon="save" color="success" :disabled="isLoading">Update</base-button>
                        </form>
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
                form: {
                    role_id: ''
                },
                errors: null,
                roles: [],
                isLoading: false
            }
        },
        mounted: function () {
            this.getRoles();
            this.getUser();
        },
        methods: {
            getRoles: function () {
                axios.get('/user/fetch-roles')
                    .then(r => r.data)
                    .then((response) => {
                        this.roles = response.data;
                    })
            },
            getUser: function () {
                axios.get('/user/fetch/' + this.$route.params.user_id)
                    .then(r => r.data)
                    .then((response) => {
                        this.form = response.data;
                        this.tags = this.form.user_roles;
                    });
            },
            editUser: function () {
                 if(this.isLoading) return;              
                this.isLoading = true; 
                axios.post('/user/edit/' + this.$route.params.user_id, this.form)
                    .then(r => r.data)
                    .then((response) => {
                        this.errors = null;
                        this.listUser();
                    }).catch((error) => {
                        this.errors = typeof error.response.data.errors !== 'undefined' ? error.response.data
                            .errors : null;
                    }).finally(()=>{
                        this.isLoading = false;
                    });
                //hidebtnLoader();
            },
            listUser: function () {
                this.$router.push({
                    path: '/users'
                });
            }
        }
    }

</script>
