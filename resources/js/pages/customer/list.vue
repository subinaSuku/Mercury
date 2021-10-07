<template>
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                Customers
            </h3>
            <router-link tag="button" type="button" :to="{ name:'customers.create'}"
                class="btn btn-primary float-right">
                <i class="fa fa-plus"></i>
                Add Customer
            </router-link>
        </div>
        <div class="row">
             <div class="col-md-12 grid-margin stretch-card accordion" id="accordion" role="tablist">
              <div class="card">
                <div class="card-header" role="tab" id="filter-customer-1">
                    <h6 class="mb-0">
                    <a data-toggle="collapse" href="#filter-customer" aria-expanded="false" aria-controls="filter-customer" class="collapsed">
                        Filter
                    </a>
                    </h6>
                </div>
                <div id="filter-customer" class="collapse" role="tabpanel" aria-labelledby="filter-customer-1" data-parent="#accordion" style="">
                    <div class="card-body">                  
                        <form class="forms-sample">
                            <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1">Column Order</label>
                                    <select class="form-control form-control-lg" v-model="filter.order_column"  id="filter-1">
                                        <option value="" disabled>Column Order</option>
                                        <option :value="'customer_no'">Customer ID</option>
                                        <option :value="'first_name'">First name</option>
                                        <option :value="'last_name'">Last name</option>
                                        <option :value="'email'">Email</option>
                                        <option :value="'mobile'">Mobile</option>
                                        <option :value="'created_at'">Created At</option>
                                        <option :value="'updated_at'">Updated At</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1">Order By</label>
                                    <select class="form-control form-control-lg" v-model="filter.order_by" id="filter-2">
                                        <option value="" disabled>Order By</option>
                                        <option :value="'asc'">Asc</option>
                                        <option :value="'desc'">Desc</option>
                                    </select>
                                </div>
                            </div>
                            </div>
                            <div class="grid-margin stretch-card float-right" style="width:100px;">
                            <button type="button" @click="updateQuery(1)" class="btn btn-primary mr-2 float-left">Filter</button>
                            </div>
                        </form>
                    </div>
                </div>
              </div>
            </div>
        </div>
        <div class="row">           
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="page-header">
                            <h4 class="card-title">Customer List</h4>
                            <div class="grid-margin stretch-card float-right" style="width:320px;">
                                <input type="text" class="form-control" name="searchText" v-model="filter.searchText" 
                                                @keyup="updateQuery(1)"     placeholder="Search... ">
                            </div>
                        </div>
                        <div class="table-sorter-wrapper col-lg-12 table-sorter-wrapper col-lg-12 table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Customer ID</th>
                                        <th>Full Name</th>
                                        <th>Email</th>
                                        <th>Mobile</th>
                                        <th>Created By</th>
                                        <th>Updated At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(user , index) in customers" :key="user.id">
                                        <td>{{ (index + 1) + (15 * (currentPage - 1)) }}</td>
                                        <td>
                                            {{ user.customer_number }}
                                        <td>
                                            <router-link tag="a" :to="{ name:'customers.view', params: { 'customer_id': user.id }}">{{ user.fullname }}</router-link>                                             
                                        </td>
                                        <td>{{ user.email }}</td>
                                        <td>{{ user.std_code }} {{ user.mobile }}</td>
                                        <td v-if="user.user">{{ user.user.fullname }}</td>
                                        <td v-else></td>
                                        <td>{{ user.updated_at | myDateTime }}</td>
                                        <td>
                                            <router-link tag="button" class="btn btn-xm btn-icon btn-info"
                                                :to="{ name:'customers.view', params: { 'customer_id': user.id }}">
                                                <span class="fa fa-eye"></span>
                                            </router-link>
                                            <button @click="removeItem(user.id , index)" 
                                                v-if="roles.includes('Super Admin')"
                                                type="button" class="btn btn-xm btn-icon btn-danger">
                                                <span class="fa fa-trash"></span>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr v-show="!customers.length">
                                        <td colspan="7" class="no-data-found-info">No customers found</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                         <div class="card-footer clearfix" v-if="totalPages > 1">
                            <paginate :page-count="totalPages" :page-range="3" :margin-pages="2"
                                :click-handler="updateQuery" :container-class="'pagination float-right'"
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
                customers: [],
                totalItems: 0,
                currentPage: null,
               
                filter: {
                    order_by: 'desc',
                    order_column: 'updated_at',
                    page: 1,
                    searchText: "",
                    
                },
                form: {},
                totalPages: 0,
                user: {},
                roles: window.roles ?? []
            };
        },
        mounted: function () {
            //this.getCustomers();
            this.updateQuery();
        },
        methods: {
            updateQuery(page = 1){
                let queries = JSON.parse(JSON.stringify(this.$route.query));
                queries.origin = this.userOrigin;
                queries.destination = this.userDestination;
                var query = this.filter.searchText;
                let filter = JSON.parse(JSON.stringify($.extend({}, queries, this.filter)));
                this.filter = filter;
                this.filter.searchText = query;
                this.filter.page = page;
                //this.$router.replace({ query: filter });
                this.getCustomers(this.filter.page ?? currentPage);
            },
            getCustomers: function (page = 1) {
                axios
                    .post("/customer/list", {
                        params: this.filter
                    })
                    .then(r => r.data)
                    .then(response => {
                        this.customers = response.data;
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
                        axios.delete('/customer/' + id)
                            .then(r => r.data)
                            .then((response) => {
                                this.customers.splice(index, 1);
                                this.$swal('Deleted', 'You successfully deleted this item', 'success');
                            });

                    } else {
                        this.$swal('Cancelled', 'Your file is still intact', 'info')
                    }
                });
            },
            createUser: function () {
                this.$router.push({
                    path: "/customers/create"
                });
            }
        }
    };

</script>
