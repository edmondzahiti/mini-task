import './bootstrap';
import { createApp } from 'vue';
import ServiceProviderApp from './components/ServiceProvider/ServiceProviderApp.vue';

// Import component styles
import '../css/components/ServiceProvider/service-provider-app.css';

// Create Vue app
const app = createApp(ServiceProviderApp);

// Mount the app
app.mount('#app');
