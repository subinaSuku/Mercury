<template>
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                Roles
            </h3>
             <router-link tag="button" type="button" :to="{ name:'roles.create'}"
                    class="btn btn-primary float-right">
                    <i class="fa fa-plus"></i>
                    Add Role
                </router-link>
        </div>
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Role List</h4>
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
                                    <tr v-for="(role , index) in roles" :key="role.id">
                                        <td>{{ (index + 1) + (15 * (currentPage - 1)) }}</td>
                                        <td>{{ role.name }}</td>
                                        <!-- <td>{{ role.guard_name }}</td> -->
                                        <td>
                                            <!-- <router-link tag="button" type="button"
                                                class="btn btn-xm btn-icon btn-warning"
                                                :to="{ name:'roles.permissions', params: { 'role_id': role.id }}">
                                                <span class="fa fa-shield-alt"></span>
                                            </router-link> -->
                                            <router-link tag="button" type="button" class="btn btn-xm btn-icon btn-info"
                                                :to="{ name:'roles.edit', params: { 'role_id': role.id }}">
                                                <span class="fa fa-edit"></span>
                                            </router-link>
                                            <button @click="removeItem(role.id , index)" type="button" class="btn btn-xm btn-icon btn-danger">
                                                <span class="fa fa-trash"></span>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr v-show="!roles.length">
                                        <td colspan="9" class="no-data-found-info">No roles found</td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>
                        <br>
                        <div class="card-footer clearfix" v-if="totalPages > 1">
                            <paginate :page-count="totalPages" :page-range="3" :margin-pages="2"
                                :click-handler="searchRoles" :container-class="'pagination float-right'"
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
                roles: [],
                totalItems: 0,
                currentPage: null,
                filter: {},
                form: {},
                totalPages: 0,
                user: {},
            }
        },
        mounted: function () {
            this.getRoles();
        },
        methods: {
            getRoles: function () {
                axios.get('/role/list')
                    .then(r => r.data)
                    .then((response) => {
                        this.roles = response.data;
                        this.totalItems = response.totalItems;
                        this.currentPage = response.currentPage;
                        this.totalPages = Math.ceil(this.totalItems / 15);
                        this.filter = response.filter;
                    });
            },
            searchRoles: function (page) {
                axios.post('/role/search/' + page, this.filter)
                    .then(r => r.data)
                    .then((response) => {
                        this.roles = response.data;
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
                        axios.delete('/role/' + id)
                            .then(r => r.data)
                            .then((response) => {
                                this.roles.splice(index, 1);
                                this.$swal('Deleted', 'You successfully deleted this item', 'success');
                            });

                    } else {
                        this.$swal('Cancelled', 'Your file is still intact', 'info')
                    }
                });
            },
            createRole: function () {
                this.$router.push({
                    path: '/roles/create'
                });
            }
        }
    }

</script>
