<template>
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                Users
            </h3>
             <router-link tag="button" type="button" :to="{ name:'users.create'}"
                    class="btn btn-primary float-right">
                    <i class="fa fa-plus"></i>
                    Add User
                </router-link>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="page-header">
                        <h4 class="card-title">User List</h4>
                        <div class="grid-margin stretch-card float-right" style="width:320px;">
                            <input type="text" class="form-control" name="searchText" v-model="filter.searchText" 
                                               @keyup="getusers(1)"     placeholder="Search... ">
                        </div>
                        </div>
                        <div class="table-sorter-wrapper col-lg-12 table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Full Name</th>
                                        <th>Email</th>
                                        <th>Status</th>
                                        <th>Role</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(user , index) in users" :key="user.id">
                                        <td>{{ (index + 1) + (15 * (currentPage - 1)) }}</td>
                                        <td>{{ user.fullname }}</td>
                                        <td>{{ user.email }}</td>
                                        <td>
                                            <span  v-if="user.is_active" class="badge badge-success badge-pill">Active</span>
                                             <span v-else class="badge badge-danger badge-pill">Inactive</span>
                                        </td>
                                         <td>
                                                <span class="badge badge-default badge-pill" v-for="role in user.roles" :key="role.id">{{ role.name }}</span>
                                            </td>
                                        <td>
                                            <router-link tag="button" type="button" class="btn btn-xm btn-icon btn-info"
                                                :to="{ name:'users.edit', params: { 'user_id': user.id }}">
                                                <span class="fa fa-edit"></span>
                                            </router-link>
                                            
                                        </td>
                                    </tr>
                                    <tr v-show="!users.length">
                                        <td colspan="7" class="no-data-found-info">No users found</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                         <div class="paginate-footer clearfix" v-if="totalPages > 1">
                            <paginate :page-count="totalPages" :page-range="3" :margin-pages="2"
                                :click-handler="getusers" :container-class="'pagination float-right'"
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
    import axios from "axios";

    export default {
        data() {
            return {
                users: [],
                totalItems: 0,
                currentPage: null,
                filter: {},
                form: {
                    order_by: 'desc',
                    page: 1,
                    column: null,
                },
                totalPages: 0,
                user: {}
            };
        },
        mounted: function () {
            this.getusers(1);
        },
        methods: {
            clickColumn: function(column,orderBy){
                this.filter.order_column = column; 
                this.filter.order_by = orderBy
                console.log(this.filter);
            },
            getusers: function (page = 1) {
                this.filter.page = page;
                axios
                    .get("/user/list", {
                        params: this.filter
                    })
                    .then(r => r.data)
                    .then(response => {
                        this.users = response.data;
                        this.totalItems = response.totalItems;
                        this.currentPage = response.currentPage;
                        this.totalPages = Math.ceil(this.totalItems / 15);
                        //this.filter = response.filter;
                    });
            },
            searchusers: function (page) {
                axios
                    .post("/user/search/" + page, this.filter)
                    .then(r => r.data)
                    .then(response => {
                        this.users = response.data;
                        this.totalItems = response.totalItems;
                        this.currentPage = response.currentPage;
                        this.totalPages = Math.ceil(this.totalItems / 15);
                        //this.filter = response.filter;
                    });
            },
            updateStatus: function(id){
                   axios
                    .post("/user/update-status/" + id)
                    .then(r => r.data)
                    .then(response => {
                        this.getusers();
                    });
            },
            createUser: function () {
                this.$router.push({
                    path: "/users/create"
                });
            }
        }
    };

</script>
