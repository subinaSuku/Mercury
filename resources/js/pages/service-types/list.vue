<template>
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                Service Types
            </h3>
            <router-link tag="button" type="button" :to="{ name:'serviceTypes.create'}"
                    class="btn btn-primary float-right">
                    <i class="fa fa-plus"></i>
                    Add Service Type
                </router-link>
        </div>
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Service Type List</h4>
                        <div class="grid-margin stretch-card float-right" style="width:320px;">
                                <input type="text" class="form-control" name="searchText" v-model="filter.searchText" 
                                                @keyup="getServiceTypes(1)"     placeholder="Search... ">
                            </div>
                        <div class="table-sorter-wrapper col-lg-12 table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item , index) in serviceTypes" :key="item.id">
                                        <td>{{ (index + 1)  }}</td>
                                        <td>{{ item.name }}</td>
                                        <td>
                                            <router-link tag="button" type="button" class="btn btn-xm btn-icon btn-info"
                                                :to="{ name:'serviceTypes.edit', params: { 'service_type_id': item.id }}">
                                                <span class="fa fa-edit"></span>
                                            </router-link>
                                            <button @click="removeItem(item.id, index)" type="button" class="btn btn-xm btn-icon btn-danger">
                                                <span class="fa fa-trash"></span>
                                            </button>
                                        </td>
                                    </tr>
                                    
                                    <tr v-show="!serviceTypes.length">
                                        <td colspan="7" class="no-data-found-info">No service types found</td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>
                         <br>
                        <div class="card-footer clearfix" v-if="totalPages > 1">
                            <paginate :page-count="totalPages" :page-range="3" :margin-pages="2"
                                :click-handler="getServiceTypes" :container-class="'pagination float-right'"
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
                serviceTypes: [],
                totalItems: 0,
                currentPage: null,
                filter: {},
                form: {},
                totalPages: 0,
                user: {}
            };
        },
        mounted: function () {
            this.getServiceTypes();
        },
        methods: {
            getServiceTypes: function (page = 1) {

                this.filter.page = page;
                axios
                    .post("/service-type/list", {
                        params: this.filter
                    })
                    .then(r => r.data)
                    .then(response => {
                        this.serviceTypes = response.data;
                        this.totalItems = response.totalItems;
                        this.currentPage = response.currentPage;
                        this.totalPages = Math.ceil(this.totalItems / 15);
                        // this.filter = response.filter;
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
                        axios.delete('/service-type/' + id)
                            .then(r => r.data)
                            .then((response) => {
                                this.serviceTypes.splice(index, 1);
                                this.$swal('Deleted', 'You successfully deleted this item', 'success');
                            });

                    } else {
                        this.$swal('Cancelled', 'Your file is still intact', 'info')
                    }
                });
            },
            createUser: function () {
                this.$router.push({
                    path: "/service-types/create"
                });
            }
        }
    };

</script>
