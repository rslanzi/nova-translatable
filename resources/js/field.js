Nova.booting(Vue => {
    Vue.config.devtools = true;

    Vue.component('index-nova-translatable', require('./components/IndexField.vue'));
    Vue.component('detail-nova-translatable', require('./components/DetailField.vue'));
    Vue.component('form-nova-translatable', require('./components/FormField.vue'));

    Vue.component('index-nova-translatable-slug-field', require('./components/Slug/IndexField.vue'));
    Vue.component('detail-nova-translatable-slug-field', require('./components/Slug/DetailField.vue'));
    Vue.component('form-nova-translatable-slug-field', require('./components/Slug/FormField.vue'));

    Vue.component('index-nova-translatable-code-field', require('./components/Code/IndexField.vue'));
    Vue.component('detail-nova-translatable-code-field', require('./components/Code/DetailField.vue'));
    Vue.component('form-nova-translatable-code-field', require('./components/Code/FormField.vue'));
});
