<template>
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                Permissions
            </h3>
            <router-link tag="button" type="button" :to="{ name:'permissions.create'}"
                class="btn btn-primary float-right">
                <i class="fa fa-plus"></i>
                Add Permission
            </router-link>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Permission List</h4>
                        <div class="table-sorter-wrapper col-lg-12 table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name </th>
                                        <!-- <th>Guard Name</th> -->
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(permission , index) in permissions" :key="permission.id">
                                        <td>{{ (index + 1) + (15 * (currentPage - 1)) }}</td>
                                        <td>{{ permission.name }}</td>
                                        <!-- <td>{{ permission.guard_name }}</td> -->
                                        <td>
                                            <router-link tag="button" type="button" class="btn btn-xm btn-icon btn-info"
                                                :to="{ name:'permissions.edit', params: { 'role_id': permission.id }}">
                                                <span class="fa fa-edit"></span>
                                            </router-link>
                                            <button @click="removeItem(permission.id , index)" type="button" class="btn btn-xm btn-icon btn-danger">
                                                <span class="fa fa-trash"></span>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr v-show="!permissions.length">
                                        <td colspan="9" class="no-data-found-info">No permissions found</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div>
                            <paginate :page-count="totalPages" :page-range="3" :margin-pages="2"
                                :click-handler="searchPermissions" :container-class="'pagination float-right'"
                                :page-class="'page-item'">
                            </paginate>
                        </div>
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
                permissions: [],
                totalItems: 0,
                currentPage: null,
                filter: {},
                form: {},
                totalPages: 0,
                user: {}
            }
        },
        mounted: function () {
            this.getPermissions();
        },
        methods: {
            getPermissions: function () {
                axios.get('/permission/list')
                    .then(r => r.data)
                    .then((response) => {
                        this.permissions = response.data;
                        this.totalItems = response.totalItems;
                        this.currentPage = response.currentPage;
                        this.totalPages = Math.ceil(this.totalItems / 15);
                        this.filter = response.filter;
                    });
            },
            searchPermissions: function (page) {
                axios.post('/permission/search/' + page, this.filter)
                    .then(r => r.data)
                    .then((response) => {
                        this.permissions = response.data;
                        this.totalItems = response.totalItems;
                        this.currentPage = response.currentPage;
                        this.totalPages = Math.ceil(this.totalItems / 15);
                        //this.filter = response.filter;
                    });
            },
            removeItem(id, index) {
                this.$swal({
                    title: 'Are you sure?',
                    text: 'You can\'t revert your action',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes Delete it!',
                    cancelButtonText: 'No, Keep it!',
                    showCloseButton: true,
                    showLoaderOnConfirm: true
                }).then((result) => {
                    if (result.value) {
                        axios.delete('/permission/' + id)
                            .then(r => r.data)
                            .then((response) => {
                                this.permissions.splice(index, 1);
                                this.$swal('Deleted', 'You successfully deleted this item', 'success');
                            });

                    } else {
                        this.$swal('Cancelled', 'Your file is still intact', 'info')
                    }
                });
            },
            createPermission: function () {
                this.$router.push({
                    path: '/permissions/create'
                });
            }
        }
    }

</script>
