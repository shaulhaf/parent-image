import VuejsClipper from 'vuejs-clipper';
import IndexField from './components/fields/IndexField';
import DetailField from './components/fields/DetailField';
import FormField from './components/fields/FormField';

Nova.booting((Vue, router) => {
  Vue.use(VuejsClipper);

  Vue.component('index-parentimage', IndexField);
  Vue.component('detail-parentimage', DetailField);
  Vue.component('form-parentimage', FormField);
});
