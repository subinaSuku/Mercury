<template>
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                Dashboard
            </h3>
        </div>
        <div class="row grid-margin">
            <div class="col-12">
                <div class="card card-statistics">
                    <div class="card-body">
                        <div class="d-flex flex-column flex-md-row align-items-center justify-content-between">
                            <router-link :to="{ name:'customers'}" tag="div" class="statistics-item">
                                <p>
                                    <i class="icon-sm fa fa-user mr-2"></i>
                                    New Customers
                                </p>
                                <h2>{{ data.new_users }}</h2>
                            </router-link>
                            <router-link  :to="{ name:'customers.bills'}" tag="div" class="statistics-item">
                                <p>
                                    <i class="icon-sm fas fa-history mr-2"></i>
                                    New Bills
                                </p>
                                <h2>{{ data.new_bills }}</h2>
                            </router-link>
                            <router-link :to="{ name:'customers.bills'}" tag="div" class="statistics-item">
                                <p>
                                    <i class="icon-sm fas fa-history mr-2"></i>
                                    Total Bills
                                </p>
                                <h2>{{ data.total_bills }}</h2>
                            </router-link>
                            <router-link :to="{ name:'customers'}" tag="div" class="statistics-item">
                                <p>
                                    <i class="icon-sm fas fa-users mr-2"></i>
                                    Total Customers
                                </p>
                                <h2>{{ data.total_users }}</h2>
                            </router-link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">
                            <i class="fas fa-users"></i>
                            Recently Added Customers
                            <button @click="navigateCustomers" class="btn btn-sm btn-default float-right">View All</button>
                        </h4>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Customer ID</th>
                                        <th>Full Name</th>
                                        <th>Mobile</th>
                                        <th>Created By</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(user , index) in customers" :key="user.id">
                                        <td>{{ index + 1 }}</td>
                                        <td>
                                            {{ user.customer_number }}
                                        <td>
                                            <router-link tag="a"
                                                :to="{ name:'customers.view', params: { 'customer_id': user.id }}">
                                                {{ user.fullname }}</router-link>
                                        </td>
                                        <td>{{ user.std_code }} {{ user.mobile }}</td>
                                        <td v-if="user.user">{{ user.user.fullname }}</td>
                                        <td v-else></td>
                                    </tr>
                                    <tr v-show="!customers.length">
                                        <td colspan="7" class="no-data-found-info">No customers found</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">
                            <i class="fas fa-calendar-alt"></i>
                            Expired bills ({{billCount}})
                            
                                <button @click="navigateBills" class="btn btn-sm btn-default float-right">View All</button>
                        </h4>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Bill No</th>
                                        <th>Account Name</th>                                        
                                        <th>Service Type</th>
                                        <th>Expiry Date</th>
                                        <th>Created By</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item , index) in bills" :key="index">    
                                         <td>{{ index + 1 }}</td>                                    
                                        <td>{{ item.bill_number }}</td>
                                        <td>{{ item.account_name }}</td>
                                        <td v-if="item.type">{{ item.type.name }}</td>
                                        <td v-else></td>
                                        <td :class="item.is_expired ==  1 ? 'txt-red' : ''">{{ item.expires_at | myDate }}</td>
                                        <td v-if="item.user">{{ item.user.fullname }}</td>
                                        <td v-else></td>
                                    </tr>
                                    <tr v-show="!bills.length">
                                        <td colspan="7" class="no-data-found-info">No Bills
                                            found</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>>

<script>
    import axios from "axios";

    export default {
        data() {
            return {
                data: {},
                customers: [],
                bills: [],
                billCount: 0
            };
        },
        mounted: function () {
            this.getData();
            this.getCustomers();
            this.getBills();
        },
        methods: {
            getData: function () {
                axios
                    .get("/dashboard-data")
                    .then(r => r.data)
                    .then(response => {
                        this.data = response;
                    });
            },
            getCustomers: function () {
                axios
                    .get("/dashboard/recently-added-customers")
                    .then(r => r.data)
                    .then(response => {
                        this.customers = response.data;
                    });
            },
            navigateCustomers: function(){
               this.$router.replace({ name: 'customers', query: {order_column:'created_at',order_by:'desc',page: 1} });
            },
            navigateBills: function(){
                this.$router.replace({ name: 'customers.bills', query: {order_column:'expires_at',order_by:'asc',is_expired:1,page: 1} });
            },
            getBills: function () {
                axios
                    .get("/dashboard/expired-bills")
                    .then(r => r.data)
                    .then(response => {
                        this.bills = response.data;
                        this.billCount = response.totalItems;
                    });
            },

        }
    };

</script>
