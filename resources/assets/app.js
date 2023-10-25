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
import FormAdd from './components/form/FormAdd'
import DataTable from './components/form/DataTable'

const app = createApp({})

app.component('form-add-vue', FormAdd)
app.component('data-table-vue', DataTable)

app.mount('#app')