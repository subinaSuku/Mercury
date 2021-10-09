<template>
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                Email Schedule
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
                        <h4 class="card-title">Email Schedule</h4>
                        <form role="form" ref="emailScheduleForm" name="emailScheduleForm"
                            v-on:submit.prevent="mailschedule">
                            <div class="card-body">
                                

                                <div class="form-group">
                                    <label for="schedule_days"> Schedule days before expire</label>
                                    <input type="number" class="form-control" name="days" v-model="form.days"
                                       min="5" max="30" placeholder="Enter number of days" v-on:keypress="isNumber($event)" required>
                                 </div>

                                    
                                
                                
                            </div>
                            <button type="button" @click="listItems()" class="btn btn-default float-right mx-3">Cancel</button>
                            <base-button :class="'btn btn-success float-right mx-3'" :type="'submit'"
                             :loading="isLoading" color="success" :disabled="isLoading">Save</base-button>
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
                    
                    
                },
                errors: null,
                              
                isLoading: false,
                
                
            }
        },
        mounted: function () { 
            this.getMailschedule();
        },
        methods: {
           
            isNumber: function(e) {
                let char = String.fromCharCode(e.keyCode);
                if(/^[0-9]+$/.test(char)) return true;
                else e.preventDefault();
            },

            getMailschedule: function () {
                axios.get('/email/schedulelist')
                    .then(r => r.data)
                    .then((response) => {
                        console.log(response);
                        this.form = response.data;
                        this.form.password = response.password;
                    });
            },
           

            mailschedule: function () {
                           
                this.isLoading = true; 
                axios.post('/email/schedule', this.form)
                    .then(r => r.data)
                    .then((response) => {
                        this.errors = null;
                        this.listItems();
                        this.viewItem(response.data.id);
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
