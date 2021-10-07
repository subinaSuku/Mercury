<template>
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                Create Customer
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
                        <h4 class="card-title">Create Service Type form</h4>
                        <form role="form" ref="addServiceTypeFrom" name="addServiceTypeFrom"
                            v-on:submit.prevent="createServiceType">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="first_name">Service Type Name</label>
                                    <input type="text" class="form-control" name="name" v-model="form.name"
                                        placeholder="Service Name" required>
                                </div>
                            </div>
                            <button type="button" @click="listItems()" class="btn btn-default">Cancel</button>
                            <base-button :class="'btn btn-success float-right'" :type="'submit'"
                             :loading="isLoading" icon="plus" color="success" :disabled="isLoading">Create</base-button>
                        </form>
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
                form: {},
                errors: null,
                roles: [],
                isLoading: false
            }
        },
        mounted: function () {},
        methods: {
            createServiceType: function () {
                if(this.isLoading) return;              
                this.isLoading = true; 
                axios.post('/service-type/create', this.form)
                    .then(r => r.data)
                    .then((response) => {
                        this.errors = null;
                        this.listItems();
                    }).catch((error) => {
                        this.errors = typeof error.response.data.errors !== 'undefined' ? error.response.data
                            .errors : null;
                    }).finally(()=>{
                        this.isLoading = false;
                    });
                //hidebtnLoader();
            },
            listItems: function () {
                this.$router.push({
                    path: '/service-types'
                });
            }
        }
    }

</script>
