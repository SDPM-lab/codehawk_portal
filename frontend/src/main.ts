import { createApp } from "vue";
import { createPinia } from "pinia";
import App from "./App.vue";
import "./registerServiceWorker";
import router from "./router";

// import store from './store';
// createApp(App).use(store).use(router).mount('#app');
createApp(App)
  .use(createPinia())
  .use(router)
  .mount("#app");
