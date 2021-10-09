<template>
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                Email Settings
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
                        <h4 class="card-title">Email Settings</h4>
                        <form role="form" ref="emailConfigForm" name="emailConfigForm"
                            v-on:submit.prevent="mailsetting">
                            <div class="card-body">
                                

                                <div class="form-group">
                                    <label for="mailer"> Mail Mailer</label>
                                    <input type="text" class="form-control" name="mailer" v-model="form.mailer"
                                        placeholder="Mail Mailer" v-on:keypress="isLetter($event)" required>
                                 </div>

                                    
                                
                                <div class="form-group">
                                    <label for="host"> Mail Host </label>
                                    <input type="text" class="form-control" name="host" v-model="form.host"
                                        placeholder="Mail Host Name"  v-on:keypress="isLetter($event)" required>
                             </div>
                                   
                    
                                
                                <div class="form-group">
                                    <label for="port"> Mail Port </label>
                                    <input type="text" class="form-control" name="port" v-model="form.port"
                                        placeholder="Mail Port" v-on:keypress="isNumber($event)"  required>
                             </div>

                             <div class="form-group">
                                    <label for="form_address">  From Address</label>
                                    <input type="email" class="form-control" name="from_address" v-model="form.from_address"
                                        placeholder="From Address"  v-on:keypress="isEmail($event)" required>
                             </div>

                             <div class="form-group">
                                    <label for="from_name"> From Name</label>
                                    <input type="text" class="form-control" name="from_name" v-model="form.from_name"
                                        placeholder="From Name" v-on:keypress="isLetter($event)"  required>
                             </div>

                             <div class="form-group">
                                    <label for="encryption">Encryption</label>
                                    <input type="text" class="form-control" name="encryption"  v-model="form.encryption"
                                        placeholder="Encryption" v-on:keypress="isLetter($event)" required>
                             </div>

                              <div class="form-group">
                                    <label for="username"> Username</label>
                                    <input type="email" class="form-control" name="username" v-model="form.username"
                                        placeholder="Username" v-on:keypress="isLetter($event)" required>
                             </div>      

                             <div class="form-group">
                                    <label for="password"> Password</label>
                                    <input type="password" class="form-control" name="password" v-model="form.password"
                                        placeholder="Password"  required>
                             </div>    

                            </div>
                            <button type="button" @click="listItems()" class="btn btn-default">Cancel</button>
                            <base-button :class="'btn btn-success float-right'" :type="'submit'"
                             :loading="isLoading" icon="plus" color="success" :disabled="isLoading">Submit</base-button>
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
                    mailer: null,
                    password:'1213245',
                },
                errors: null,
                roles: [],                
                isLoading: false,
                
                
            }
        },
        mounted: function () { 
            this.getMailconfig();
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

            getMailconfig: function () {
                axios.get('/email/list')
                    .then(r => r.data)
                    .then((response) => {
                        console.log(response);
                        this.form = response.data;
                         this.form.password = response.password;
                    });
            },

            mailsetting: function () {
                           
                this.isLoading = true; 
                axios.post('/email/create', this.form)
                    .then(r => r.data)
                    .then((response) => {
                        this.errors = null;
                        this.listItems();
                        //this.viewItem(response.data.id);
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
                    path: '/'
                });
            }
        }
    }

</script>
