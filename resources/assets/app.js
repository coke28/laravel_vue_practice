import Echo from "laravel-echo"

window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    wsHost: window.location.hostname,
    wsPort: 6001,
    wssPort: 6001,
    forceTLS: false,
    disableStats: true,
    enabledTransports: ['ws', 'wss'],
});

import { createApp } from 'vue'

import DataTable from './components/datatable/DataTable'
import Tabs from './components/tabs/Tabs'
import CustomForm from './components/form/CustomForm'
import Loader from './components/loader/Loader'

const app = createApp({})

app.component('data-table-vue', DataTable)
app.component('custom-form-vue', CustomForm)
app.component('tabs-vue', Tabs)
app.component('loader-vue', Loader)

app.mount('#app')