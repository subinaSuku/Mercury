<template>
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                Customers
            </h3>
             <button type="button" @click="showService()" class="btn btn-primary float-right">
                <i class="fa fa-plus"></i>
                Add Service
            </button>
        </div>
         
        <div class="row">
            <div class="col-md-4 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Customer Information</h4>
                        <div class="py-4">
                            <p class="clearfix">
                                <span class="float-left">
                                    Customer Id
                                </span>
                                <span class="float-right text-muted">
                                    {{ customer.customer_number }}
                                </span>
                            </p>
                            <p class="clearfix">
                                <span class="float-left">
                                    Full Name
                                </span>
                                <span class="float-right text-muted">
                                    {{ customer.fullname }}
                                </span>
                            </p>
                            <!-- <p class="clearfix">
                                <span class="float-left">
                                    Last Name
                                </span>
                                <span class="float-right text-muted">
                                    {{ customer.last_name }}
                                </span>
                            </p> -->
                            <p class="clearfix">
                                <span class="float-left">
                                    Mobile
                                </span>
                                <span class="float-right text-muted">
                                    {{ customer.phone }}
                                </span>
                            </p>
                            <p class="clearfix" v-if="customer.email">
                                <span class="float-left">
                                    Email
                                </span>
                                <span class="float-right text-muted">
                                    {{ customer.email }}
                                </span>
                            </p>
                            <p class="clearfix">
                                <span class="float-left">
                                    Created At
                                </span>
                                <span class="float-right text-muted">
                                    {{ customer.created_at | myDateTime }}
                                </span>
                            </p>
                            <p class="clearfix">
                                <span class="float-left">
                                    Updated At
                                </span>
                                <span class="float-right text-muted">
                                    {{ customer.updated_at | myDateTime }}
                                </span>
                            </p>
                            <p class="clearfix" v-if="customer.user">
                                <span class="float-left">
                                    Created Name
                                </span>
                                <span class="float-right text-muted">
                                    {{ customer.user.fullname }}
                                </span>
                            </p>
                        </div>
                        <router-link tag="button" type="button"
                            :to="{ name:'customers.edit', params: { 'customer_id': customer.id }}"
                            class="btn btn-primary btn-sm">
                            Edit
                        </router-link>
                    </div>
                </div>
            </div>
            <div class="col-md-8 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="page-header">
                            <h4 class="card-title">Customer Bills</h4>
                            <div class="grid-margin stretch-card float-right" style="width:320px;">
                                <input type="text" class="form-control" name="searchText" v-model="filter.searchText" 
                                                     placeholder="Search... ">
                                   
                            </div>                            
                        </div>
                        <!-- <div class="row">
                            <div class="col-2"></div>
                            <div class="col-4">
                                    <div class="form-group">
                                        <select class="form-control form-control-lg" v-model="filter.order_column"  id="filter-1">
                                            <option :value="'bill_number'">Bill No</option>
                                            <option :value="'account_name'">Account Name</option>
                                        </select>
                                    </div>
                            </div>
                            <div class="col-2">
                                    <div class="form-group">
                                        <select class="form-control form-control-lg" v-model="filter.order_by" id="filter-2">
                                            <option :value="'asc'">Asc</option>
                                            <option :value="'desc'">Desc</option>
                                        </select>
                                    </div>
                            </div>
                            <div class="col-10">
                                
                            </div>
                        </div> -->
                         
                        <div class="table-sorter-wrapper col-lg-12 table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Bill No</th>
                                        <th>Account Name</th>                                        
                                        <th>Service Type</th>
                                        <th>Expiry Date</th>
                                        <th>Created By</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item , index) in orderedServices" :key="index">    
                                         <td>{{ index + 1 }}</td>                                    
                                        <td>{{ item.bill_number }}</td>
                                        <td>{{ item.account_name }}</td>
                                        <td v-if="item.type">{{ item.type.name }}</td>
                                        <td v-else></td>
                                        <td>{{ item.expires_at | myDate }}</td>
                                        <td v-if="item.user">{{ item.user.fullname }}</td>
                                        <td v-else></td>
                                        <td>
                                            <button type="button" class="btn btn-xm btn-icon btn-info"
                                                @click="editService(item)">
                                                <span class="fa fa-edit"></span>
                                            </button>
                                            <button @click="removeItem(item.id , index)" type="button" 
                                             v-if="roles.includes('Super Admin')"
                                            class="btn btn-xm btn-icon btn-danger">
                                                <span class="fa fa-trash"></span>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr v-show="!orderedServices.length">
                                        <td colspan="7" class="no-data-found-info">No bills
                                            found</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="updateService" role="dialog" aria-labelledby="ModalLabel">
            <div class="modal-dialog" role="document">
                <form role="form" ref="updateServiceFrom" name="updateServiceFrom" v-on:submit.prevent="updateService">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ModalLabel">Add / Update Service</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="service_type_id" class="col-form-label">Service Type:</label>
                                <input v-if="form.id != null" type="text" class="form-control" name="account_name" v-model="form.type.name" :disabled="true" required>
                                <select v-else name="service_type_id" id="service_type_id" class="form-control"
                                    v-model="form.service_type_id" required>
                                    <option value="" disabled>Select Bill Type</option>
                                    <option v-for="type in serviceTypes" :key="type.id" :value="type.id">{{ type.name }}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="account_name" class="col-form-label">Account Name:</label>
                                <input type="text" class="form-control" name="account_name" v-on:keypress="isLetter($event)" v-model="form.account_name"
                                    placeholder="Account Name" required>
                            </div>
                            <div class="form-group">
                                <label for="bill_number" class="col-form-label">Bill Number:</label>
                                <input type="text" class="form-control" name="bill_number" v-model="form.bill_number"
                                    placeholder="Bill Number" required>
                            </div>
                            <div class="form-group">
                                <label for="expired_at" class="col-form-label">Expiry Date:</label>
                                 <date-picker :config="options" class="form-control" 
                                    name="expired_at"
                                    autocomplete="off"
                                    v-model="form.expired_at" placeholder="Expiry Date"></date-picker>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button  type="button" class="btn btn-light float-left" data-dismiss="modal">Close</button>                            
                            <base-button :class="'btn btn-success float-right'"  :type="'submit'" v-if="form.id != null"
                             :loading="isLoading" icon="save" color="success" :disabled="isLoading">Update</base-button>
                            <base-button :class="'btn btn-success float-right'"  :type="'submit'" v-else
                             :loading="isLoading" icon="plus" color="success" :disabled="isLoading">Create</base-button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</template>

<style scoped>
.card-body p span{
    font-size: 16px;
    font-weight: 700;
}

</style>

<script>
    import axios from "axios";  
    import datePicker from 'vue-bootstrap-datetimepicker';

    export default {
        components: {
            datePicker
        },
        data() {
            return {
                options: {
                    format: 'DD-MM-YYYY',
                    useCurrent: false,
                },
                customer: {},
                services: [],
                form: {
                    service_type_id: '',
                    expired_at: null,
                },
                expires_at: null,
                serviceTypes: [],
                filter:{
                    searchText: "",
                    order_column: "id",
                    order_by: 'desc'
                },
                roles: window.roles,
                isLoading: false
            };
        },
        computed: {          
            orderedServices: function () {
                let results = this.services;
                let filter = this.filter;
                if(filter.searchText != "" || filter.searchText != null)
                results = results.filter(item => item.bill_number.toLowerCase().includes(filter.searchText.toLowerCase()) || item.account_name.toLowerCase().includes(filter.searchText.toLowerCase()));
                if(filter.order_cloumn != "" )
                return _.orderBy(results, filter.order_cloumn ?? 'id' , filter.order_by ?? 'desc');
                return results;
            }
        },
        mounted: function () {
            this.getCustomer();
            this.getServiceTypes();
        },
        methods: {
            isLetter: function(e)  {
                let char = String.fromCharCode(e.keyCode);
                if(/^[A-Za-z\s]+$/.test(char)) return true;
                else e.preventDefault();
            },
            isNumber: function(e) {
                let char = String.fromCharCode(e.keyCode);
                if(/^[0-9]+$/.test(char)) return true;
                else e.preventDefault();
            },
            getServiceTypes: function () {
                axios
                    .get("/service-type/list", {
                        params: this.filter
                    })
                    .then(r => r.data)
                    .then(response => {
                        this.serviceTypes = response.data;
                    });
            },
            getCustomer: function () {
                axios.get('/customer/fetch/' + this.$route.params.customer_id)
                    .then(r => r.data)
                    .then((response) => {
                        this.customer = response.data;
                        this.services = this.customer.services;
                    });
            },
            showService() {
                this.form = {
                    service_type_id: '',
                    expired_at: null,
                };
                $('#updateService').modal('show');
            },
            editService(service) {
                console.log(service);
                this.form = service;
                $('#updateService').modal('show');
            },
            getCustomers: function () {
                axios
                    .get("/customer/list", {
                        params: this.filter
                    })
                    .then(r => r.data)
                    .then(response => {
                        this.customers = response.data;
                        this.totalItems = response.totalItems;
                        this.currentPage = response.currentPage;
                        this.totalPages = Math.ceil(this.totalItems / 15);
                        this.filter = response.filter;
                    });
            },
            updateService: function () {
                if(this.isLoading) return;              
                this.isLoading = true; 
                axios.post('/customer/' +  this.$route.params.customer_id + '/update-service', this.form)
                    .then(r => r.data)
                    .then((response) => {
                        this.errors = null;
                        this.getCustomer();
                        
                    }).catch((error) => {
                        this.errors = typeof error.response.data.errors !== 'undefined' ? error.response.data
                            .errors : null;
                    }).finally(() => {
                        this.form = {};
                        this.isLoading = false;
                        $('#updateService').modal('hide');
                    });
                //hidebtnLoader();
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
                        axios.delete('/customer/service-type/' + id)
                            .then(r => r.data)
                            .then((response) => {
                                this.getCustomer();
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
