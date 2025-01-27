// src/plugins/primevue.js
import PrimeVue from 'primevue/config';
import Button from 'primevue/button';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';

export default (app) => {
  app.use(PrimeVue);
  app.component('Button', Button);
  app.component('DataTable', DataTable);
  app.component('Column', Column);
};
