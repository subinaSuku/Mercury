let routes = [{
        path: '/dashboard',
        component: require('../pages/dashboard.vue').default, 
        name: 'dashboard',
        meta: { title: 'Dashboard - Mercury' }
    },
    {
        path: '/',
        redirect: '/dashboard',
    },
    {
        path: '/users',
        component: require('../pages/index.vue').default,
        children: [{
                path: '',
                component: require('../pages/user/list.vue').default,
                name: 'users',
                meta: { title: 'Users - Mercury' }
            },
            {
                path: 'create',
                component: require('../pages/user/create.vue').default,
                name: 'users.create',
                meta: { title: 'Create User - Mercury' }
            },
            {
                path: ':user_id/edit',
                component: require('../pages/user/edit.vue').default,
                name: 'users.edit',
                meta: { title: 'Edit User - Mercury' }
            },
        ]
    },
    {
        path: '/customers',
        component: require('../pages/index.vue').default,
        children: [{
                path: '',
                component: require('../pages/customer/list.vue').default,
                name: 'customers',
                meta: { title: 'Customers - Mercury' }
            },
            {
                path: 'create',
                component: require('../pages/customer/create.vue').default,
                name: 'customers.create',
                meta: { title: 'Create Customer - Mercury' }
            },
            {
                path: ':customer_id/edit',
                component: require('../pages/customer/edit.vue').default,
                name: 'customers.edit',
                meta: { title: 'Edit Customer - Mercury' }
            },
            {
                path: ':customer_id',
                component: require('../pages/customer/view.vue').default,
                name: 'customers.view',
                meta: { title: 'Customer Profile - Mercury' }
            },
            {
                path: 'service/bills',
                component: require('../pages/customer/bills.vue').default,
                name: 'customers.bills',
                meta: { title: 'Customer Bills - Mercury' }
            },
        ]
    },
    {
        path: '/service-types',
        component: require('../pages/index.vue').default,
        children: [{
                path: '',
                component: require('../pages/service-types/list.vue').default,
                name: 'serviceTypes',
                meta: { title: 'Service Types - Mercury' }
            },
            {
                path: 'create',
                component: require('../pages/service-types/create.vue').default,
                name: 'serviceTypes.create',
                meta: { title: 'Create Service Type - Mercury' }
            },
            {
                path: ':service_type_id/edit',
                component: require('../pages/service-types/edit.vue').default,
                name: 'serviceTypes.edit',
                meta: { title: 'Edit Service Type - Mercury' }
            },    
        ]
    },  
    {
        path: '/roles',
        component: require('../pages/index.vue').default,
        children: [{
                path: '',
                component: require('../pages/role/list.vue').default,
                name: 'roles',
                meta: { title: 'Roles - Mercury' }
            },
            {
                path: 'create',
                component: require('../pages/role/create.vue').default,
                name: 'roles.create',
                meta: { title: 'Create Role - Mercury' }
            },
            {
                path: ':role_id/edit',
                component: require('../pages/role/edit.vue').default,
                name: 'roles.edit',
                meta: { title: 'Edit Role - Mercury' }
            },
            {
                path: ':role_id/permissions',
                component: require('../pages/role/permission.vue').default,
                name: 'roles.permissions',
                meta: { title: 'Role Permissions - Mercury' }
            },            
        ]
    },
    {
        path: '/permissions',
        component: require('../pages/permission/index.vue').default,
        children: [{
                path: '',
                component: require('../pages/permission/list.vue').default,
                name: 'permissions',
                meta: { title: 'Permissions - Mercury' }
            },
            {
                path: 'create',
                component: require('../pages/permission/create.vue').default,
                name: 'permissions.create',
                meta: { title: 'Create Permission - Mercury' }
            },
            {
                path: ':permission_id/edit',
                component: require('../pages/permission/edit.vue').default,
                name: 'permissions.edit',
                meta: { title: 'Edit Permission - Mercury' }
            },
        ]
    },

    {
        path: '/email-settings',
        component: require('../pages/index.vue').default,
        children: [
            {
                path: 'create',
                component: require('../pages/email-settings/create.vue').default,
                name: 'emailSetting.create',
                meta: { title: 'Create Email Settings - Mercury' }
            },
            {
                path: 'schedule',
                component: require('../pages/email-settings/schedule.vue').default,
                name: 'emailSetting.schedule',
                meta: { title: 'Schedule Email Notification - Mercury' }
            },    
        ]
    },  



    {
        path: '*',
        redirect: '/dashboard',
        // component: require('../components/NotFound.vue').default,
        // meta: { title: 'Page Not Found - Mercury' }
    },
];

export default routes;
