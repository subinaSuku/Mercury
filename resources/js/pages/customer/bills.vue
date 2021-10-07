<template>
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                Customer Bills
            </h3>
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
                                            <option value="" disabled>Column order</option>
                                            <option :value="'bill_number'">Bill No</option>
                                            <option :value="'account_name'">Account Name</option>
                                            <option :value="'created_at'">Created At</option>
                                            <option :value="'updated_at'">Updated At</option>
                                            <option :value="'expires_at'">Expiry Date</option>                                            
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
                                <div class="col-2">
                                    <div class="form-group">
                                        <label for="exampleFormControlSelect1">Expired Only</label>
                                        <select class="form-control form-control-lg" v-model="filter.is_expired" id="filter-2">
                                            <option :value="'0'">No</option>
                                            <option :value="'1'">Yes</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="form-group">
                                        <label for="exampleFormControlSelect1">Expire in (Days)</label>
                                        <select class="form-control form-control-lg" v-model="filter.expire_in" id="filter-2">
                                            <option :value="'0'"></option>
                                            <option v-for="i in 15" :value="i" :key="i">{{i}}</option>
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
                            <h4 class="card-title">Customer Bills</h4>
                            <div class="grid-margin stretch-card float-right" style="width:320px;">
                                <input type="text" class="form-control" name="searchText" v-model="filter.searchText" 
                                @keyup="updateQuery(1)"
                                                     placeholder="Search... ">
                                   
                            </div>                            
                        </div>
                         
                        <div class="table-sorter-wrapper col-lg-12 table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Bill No</th>
                                        <th>Account Name</th>                                        
                                        <th>Service Type</th>
                                        <th>Expiry Date</th>
                                        <th>Customer</th>
                                        <th>Updated At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item , index) in bills" :key="index">    
                                        <td>{{ (index + 1) + (15 * (currentPage - 1)) }}</td>                                    
                                        <td>{{ item.bill_number }}</td>
                                        <td>{{ item.account_name }}</td>
                                        <td v-if="item.type">{{ item.type.name }}</td>
                                        <td v-else></td>
                                        <td :class="item.is_expired ==  1 ? 'txt-red' : ''">{{ item.expires_at | myDate }}</td>
                                        <td v-if="item.customer">
                                            <router-link tag="a" :to="{ name:'customers.view', params: { 'customer_id': item.customer.id }}">
                                                {{ item.customer.fullname }}
                                            </router-link> 
                                        </td>
                                        <td v-else></td>
                                        <td>{{ item.updated_at | myDateTime }}</td>
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
                                    <tr v-show="!bills.length">
                                        <td colspan="7" class="no-data-found-info">No bills
                                            found</td>
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
                                    v-model="form.service_type_id" :disabled="form.id != null" required>
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
                                    v-model="form.expired_at" placeholder="Expiry Date"></date-picker>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button  type="button" class="btn btn-light float-left" data-dismiss="modal">Close</button>                            
                            <base-button :class="'btn btn-success float-right'" :type="'submit'"
                             :loading="isLoading" icon="save" color="success" :disabled="isLoading">Save</base-button>
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
    import 'pc-bootstrap4-datetimepicker/build/css/bootstrap-datetimepicker.css';

    export default {
        components: {
            datePicker
        },
        data() {
            return {
                bills: {},
                totalItems: 0,
                currentPage: null,
                totalPages: 0,
                filter:{
                    searchText: "",
                    order_column: "updated_at",
                    order_by: 'desc',
                    
                },
                roles: window.roles ?? [],
                options: {
                    format: 'DD-MM-YYYY',
                    useCurrent: false,
                },
                isLoading: false,
                form: {
                    expired_at: null
                },
                serviceTypes: [],
            };
        },
        mounted: function () {
           
            this.updateQuery();
            this.getServiceTypes();
            
            
        },
        methods: {
            getServiceTypes: function () {
                axios
                    .post("/service-type/list", {
                        params: this.filter
                    })
                    .then(r => r.data)
                    .then(response => {
                        this.serviceTypes = response.data;
                    });
            },
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
                this.getBills(this.filter.page ?? currentPage);
            },
            editService(service) {
                this.form = service;
                $('#updateService').modal('show');
            },
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
            getBills: function (page = 1) {
                this.filter.page = page;
                
                axios
                    .get("/customer/bills", {
                        params: this.filter
                    })
                    .then(r => r.data)
                    .then(response => {
                        this.bills = response.data;
                        this.totalItems = response.totalItems;
                        this.currentPage = response.currentPage;
                        this.totalPages = Math.ceil(this.totalItems / 15);
                        //this.filter = response.filter;
                    });
            },
             updateService: function () {
                if(this.isLoading) return;              
                this.isLoading = true; 
                axios.post('/customer/' +  this.form.customer.id + '/update-service', this.form)
                    .then(r => r.data)
                    .then((response) => {
                        this.errors = null;
                        this.updateQuery(this.filter.page);
                        
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
                                this.updateQuery(this.filter.page);
                                this.$swal('Deleted', 'You successfully deleted this item', 'success');
                            });

                    } else {
                        this.$swal('Cancelled', 'Your file is still intact', 'info')
                    }
                });
            },
        }
    };

</script>
