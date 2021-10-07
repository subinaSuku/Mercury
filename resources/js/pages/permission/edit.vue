<template>
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                Edit Permission
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
                        <h4 class="card-title">Edit Permission Form</h4>
                        <form role="form" ref="editPermissionFrom" name="editPermissionFrom"
                            v-on:submit.prevent="editPermission">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name">Permission Name</label>
                                    <input type="text" class="form-control" name="name" v-model="form.name"
                                        placeholder="Permission Name" required>
                                </div>
                                <!-- <div class="form-group">
                                        <label for="guard_name">Guard Name</label>
                                        <input type="text" class="form-control" name="guard_name" v-model="form.guard_name" 
                                            placeholder="Guard Name">
                                    </div> -->

                                <!-- /.box-body -->
                            </div>
                            <div class="card-footer">
                                <button type="button" @click="listPermission()" class="btn btn-default">Cancel</button>
                                <button type="submit" class="btn btn-success float-right">Update</button>
                            </div>
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
            }
        },
        mounted: function () {
            this.getPermission();
        },
        methods: {
            getPermission: function () {
                axios.get('/permission/fetch/' + this.$route.params.role_id)
                    .then(r => r.data)
                    .then((response) => {
                        this.form = response.data;
                    });
            },
            editPermission: function () {
                axios.post('/permission/edit/' + this.$route.params.role_id, this.form)
                    .then(r => r.data)
                    .then((response) => {
                        this.errors = null;
                        this.listPermission();
                    }).catch((error) => {
                        this.errors = typeof error.response.data.errors !== 'undefined' ? error.response.data
                            .errors : null;
                    });
                //hidebtnLoader();
            },
            listPermission: function () {
                this.$router.push({
                    path: '/permissions'
                });
            }
        }
    }

</script>
