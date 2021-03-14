Nova.booting((Vue, router, store) => {
    // Vue.config.devtools = true;

    Vue.filter('striphtml', function (value) {
        var div = document.createElement("div");
        div.innerHTML = value;
        var text = div.textContent || div.innerText || "";
        return text;
    });

    Vue.component('index-nova-translatable', require('./components/IndexField'))
    Vue.component('detail-nova-translatable', require('./components/DetailField'))
    Vue.component('form-nova-translatable', require('./components/FormField'))

    Vue.component('index-nova-translatable-slug-field', require('./components/Slug/IndexField'))
    Vue.component('detail-nova-translatable-slug-field', require('./components/Slug/DetailField'))
    Vue.component('form-nova-translatable-slug-field', require('./components/Slug/FormField'))

    Vue.component('index-nova-translatable-code-field', require('./components/Code/IndexField'))
    Vue.component('detail-nova-translatable-code-field', require('./components/Code/DetailField'))
    Vue.component('form-nova-translatable-code-field', require('./components/Code/FormField'))
})
