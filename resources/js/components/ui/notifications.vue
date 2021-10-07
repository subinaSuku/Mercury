<template>
    <li class="nav-item dropdown" v-if="notifications !=0 || expireSoon != 0">
            <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#" data-toggle="dropdown">
              <i class="fas fa-bell mx-0"></i>
              <span class="count">{{notifications + expireSoon}}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
              <a class="dropdown-item" v-if="notifications != 0">
                <p class="mb-0 font-weight-normal float-left">{{notifications}} bill(s) expired.
                </p>
                <span @click="navigateBills" class="badge badge-pill badge-warning float-right">View all</span>
              </a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" v-if="expireSoon != 0">
                <p class="mb-0 font-weight-normal float-left">{{expireSoon}} bill(s) expire in 15 days.
                </p>
                <span @click="expireSoons" class="badge badge-pill badge-warning float-right">View all</span>
              </a>
              <div class="dropdown-divider"></div>
              <!-- <a class="dropdown-item preview-item">
                <div class="preview-thumbnail">
                  <div class="preview-icon bg-danger">
                    <i class="fas fa-exclamation-circle mx-0"></i>
                  </div>
                </div>
                <div class="preview-item-content">
                  <h6 class="preview-subject font-weight-medium">Application Error</h6>
                  <p class="font-weight-light small-text">
                    Just now
                  </p>
                </div>
              </a>
              <div class="dropdown-divider"></div> -->
            </div>
          </li>


</template>>

<script>
    import axios from "axios";

    export default {
        data() {
            return {
                notifications: 0,
                expireSoon: 0
            };
        },
        mounted: function () {
            this.getBills();
        },
        methods: {
            getBills: function () {
                axios
                    .get("/dashboard/expired-bills")
                    .then(r => r.data)
                    .then(response => {
                        this.notifications = response.totalItems;
                        this.expireSoon = response.expire_soon;
                    });
            },
            expireSoons: function(){
              this.$router.push({ name: 'customers.bills', query: {expire_in:15} }).catch(() => {});
              window.location.reload();
            },
            navigateBills: function(){
                this.$router.push({ name: 'customers.bills', query: {is_expired:1} }).catch(() => {});
                window.location.reload();
            },
        }
    };

</script>
