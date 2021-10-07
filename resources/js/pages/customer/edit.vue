<template>
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                Edit Customer
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
                        <h4 class="card-title">Edit customer form</h4>
                        <form role="form" ref="editCustomerFrom" name="editCustomerFrom"
                            v-on:submit.prevent="editCustomer">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="first_name">First Name</label>
                                    <input type="text" class="form-control" name="first_name" v-on:keypress="isLetter($event)" v-model="form.first_name"
                                        placeholder="First Name" required>
                                </div>
                                <div class="form-group">
                                    <label for="last_name">Last Name</label>
                                    <input type="text" class="form-control" name="last_name" v-on:keypress="isLetter($event)" v-model="form.last_name"
                                        placeholder="Last Name" required>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" name="email" v-model="form.email"
                                        placeholder="Email address">
                                </div>
                                <div class="form-group" v-if="form.id != null">
                                    <label for="mobile">Mobile</label>
                                    <div class="row"> 
                                        <div class="col-md-12">
                                        <vue-tel-input 
                                            :class="!mobile_isValid ? 'form-control mobile-number' : 'form-control  mobile-number'" :required="true"
                                            :placeholder="'Enter mobile number'" 
                                            :defaultCountry="form.country_code"
                                            :enabledCountryCode="true" 
                                            :disabledFormatting="true"
                                            :disabledFetchingCountry="true" 
                                            :dropdownOptions="dropdownOptions"
                                            @validate="CheckValidation"
                                            :validCharactersOnly="true"
                                            :autoFormat='false'
                                            :invalidMsg="invalidMsg"
                                            :mode="'national'"
                                            :inputOptions="{'required': true}"
                                            v-model="form.mobile">
                                        </vue-tel-input>

                                        <!-- :preferredCountries="['in']" 
                                        @onInput="onInput" -->
                                            <!-- <input type="text" class="form-control" name="mobile" min="10" v-on:keypress="isNumber($event)"  v-model="form.mobile"
                                                placeholder="mobile number" required> -->
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <button type="button" @click="listItems()" class="btn btn-default">Cancel</button>
                            <base-button :class="'btn btn-success float-right'" :type="'submit'"
                             :loading="isLoading" icon="save" color="success" :disabled="isLoading">Update</base-button>
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
                form: {
                    id: null,
                    std_code: '91',
                    country_code: null
                },
                isLoading: false,
                errors: null,
                mobile_isValid: true,
                dropdownOptions: {
                    showDialCodeInSelection: true,
                    showFlags:false
                },
                invalidMsg:"Invalid mobile number",
            }
        },
        mounted: function () {
            this.getCustomer();
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
            getCustomer: function () {
                axios.get('/customer/fetch/' + this.$route.params.customer_id)
                    .then(r => r.data)
                    .then((response) => {
                        this.form = response.data;
                    });
            },
            editCustomer: function () {F
                if(this.isLoading && !this.mobile_isValid) return;              
                this.isLoading = true; 
                axios.post('/customer/edit/' + this.$route.params.customer_id, this.form)
                    .then(r => r.data)
                    .then((response) => {
                        this.errors = null;
                        
                        //this.listItems();
                        this.viewItem(response.data.id);
                    }).catch((error) => {
                        this.errors = typeof error.response.data.errors !== 'undefined' ? error.response.data
                            .errors : null;
                    }).finally(()=>{
                        this.isLoading = false;
                    });
                //hidebtnLoader();
            },
            viewItem: function (id) {
                this.$router.push({
                    name: 'customers.view' ,  params: { 'customer_id': id }
                });
            },
            onInput(number ,phoneObject) {
                this.mobile_isValid = phoneObject.valid;
            },
            CheckValidation: function (phoneObject) {
                this.mobile_isValid = phoneObject.valid;
                this.form.mobile = phoneObject.nationalNumber;
                this.form.std_code = phoneObject.countryCallingCode;
                this.form.country_code = phoneObject.countryCode;
            },
            listItems: function () {
                this.$router.pushF({
                    path: '/customers'
                });
            }
        }
    }

</script>
