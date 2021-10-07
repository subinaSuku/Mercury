<template>
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                Edit Role
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
                        <h4 class="card-title">Edit Role Form</h4>
                         <form role="form" ref="editRoleFrom" name="editRoleFrom" v-on:submit.prevent="editRole">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="name">Role Name</label>
                                        <input type="text" class="form-control" name="name" v-model="form.name"
                                            placeholder="Role Name" required>
                                    </div>
                                    <!-- <div class="form-group">
                                        <label for="guard_name">Guard Name</label>
                                        <input type="text" class="form-control" name="guard_name" v-model="form.guard_name"
                                            placeholder="Guard Name">
                                    </div> -->

                                    <!-- /.box-body -->
                                </div>
                                <button type="button" @click="listRole()" class="btn btn-default">Cancel</button>
                                <base-button :class="'btn btn-success float-right'" :type="'submit'"
                                    :loading="isLoading" icon="save" color="success" :disabled="isLoading">Update</base-button>
                            </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style scope>
    .text-red {
        color: red !important;
        padding-left: 10px;
    }

</style>

<script>
    import axios from 'axios';

    export default {
        data() {
            return {
                form: {},
                errors: null,
                isLoading: false
            }
        },
        mounted: function () {
            this.getRole();
        },
        methods: {
            getRole: function () {
                axios.get('/role/fetch/' + this.$route.params.role_id)
                    .then(r => r.data)
                    .then((response) => {
                        this.form = response.data;
                    });
            },
            editRole: function () {
                if(this.isLoading) return;              
                this.isLoading = true; 
                axios.post('/role/edit/' + this.$route.params.role_id, this.form)
                    .then(r => r.data)
                    .then((response) => {
                        this.errors = null;
                        this.listRole();
                    }).catch((error) => {
                        this.errors = typeof error.response.data.errors !== 'undefined' ? error.response.data
                            .errors : null;
                    }).finally(()=>{
                        this.isLoading = false;
                    });
                //hidebtnLoader();
            },
            listRole: function () {
                this.$router.push({
                    path: '/roles'
                });
            }
        }
    }

</script>
