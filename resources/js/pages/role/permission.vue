<template>
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                Update Permissions
            </h3>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title"> Update Permission Form</h4>
                        <form role="form" ref="editPermissionFrom" name="editPermissionFrom"
                            v-on:submit.prevent="update">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 col-sm-6" v-for="permission in permissions"
                                        :key="permission.id">
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" :id="permission.name"
                                                v-model="role.role_permissions" :value="permission.name">
                                            <label :for="permission.name" class="custom-control-label"
                                                style="text-transform:capitalize;">{{permission.name}}</label>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                            </div>
                            <button type="button" @click="listRoles()" class="btn btn-default">Cancel</button>
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
                role: {},
                permissions: [],
                errors: null,
                isLoading: false,
            }
        },
        mounted: function () {
            this.getPermissions();
            this.getRolePermissions();
        },
        methods: {
            getRolePermissions: function () {
                axios.get('/role/fetch/' + this.$route.params.role_id)
                    .then(r => r.data)
                    .then((response) => {
                        this.role = response.data;
                    });
            },
            getPermissions: function () {
                axios.get('/permission/list')
                    .then(r => r.data)
                    .then((response) => {
                        this.permissions = response.data;
                    });
            },
            update: function (page) {
                if(this.isLoading) return;              
                this.isLoading = true; 
                axios.post('/role/' + this.$route.params.role_id + '/update/permissions', this.role
                        .role_permissions)
                    .then(r => r.data)
                    .then((response) => {
                        this.role = response.data;
                        this.$swal("Update role permission", 'Role permissions updated successfully.!',
                        'success');
                    }).catch((error) => {
                        this.$swal("Update role permission", 'Role permissions updated Faild.!', 'danger');
                    }).finally(()=>{
                        this.isLoading = false;
                    });
            },
            listRoles: function () {
                this.$router.push({
                    path: '/roles'
                });
            }
        }
    }

</script>
