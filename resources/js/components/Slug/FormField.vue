<template>
    <default-field :field="field">
        <template slot="field">
            <a 
                class="inline-block font-bold cursor-pointer mr-2 animate-text-color select-none" 
                :class="{ 'text-60': localeKey !== currentLocale, 'text-primary border-b-2': localeKey === currentLocale }"
                :key="`a-${localeKey}`" 
                v-for="(locale, localeKey) in field.locales"
                @click="changeTab(localeKey)"
            >
                {{ locale }}
            </a>

            <input
                ref="slug"
                :id="field.name"
                type="text"
                class="mt-4 w-full form-control form-input form-input-bordered"
                :class="errorClasses"
                :placeholder="field.name"
                v-model="value[currentLocale]"
            />

            <charcounter 
                ref="counted" 
                :currentLocale="currentLocale" 
                :value="value[currentLocale] || ''" 
                :max-chars="field.maxChars" 
                :warning-threshold="field.warningAt" 
                v-if="field.counted"></charcounter>

            <p v-if="hasError" class="my-2 text-danger">
                {{ firstError }}
            </p>
        </template>
    </default-field>
</template>

<script>
import Charcounter from '../Charcounter/Charcounter';

import { FormField, HandlesValidationErrors, Errors } from 'laravel-nova';
import { EventBus } from '../../event-bus';

export default {
    mixins: [FormField, HandlesValidationErrors],

    props: ['resourceName', 'resourceId', 'field'],

    components: { Charcounter },

    data() {
        return {
            locales: Object.keys(this.field.locales),
            currentLocale: null,
            validationErrors: new Errors(),
            updating: false,
            initialValue: ''
        }
    },

    /**
     * Mount the component.
     */
    mounted() {
        this.currentLocale = this.locales[0] || null;

        Nova.$on('field-update-' + this.field.name, ({value}) => {
            this.generateSlug(value)
        })

        EventBus.$on('localeChanged', locale => {
            if(this.currentLocale !== locale) {
                this.changeTab(locale, true);
            }
        });
    },

    methods: {
        /*
         * Set the initial, internal value for the field.
         */
        setInitialValue() {
            this.value = this.field.value || ''
            this.initialValue = this.value
            if (this.value) {
                this.updating = true
            }
        },

        /**
         * Fill the given FormData object with the field's internal value.
         */
        fill(formData) {
            Object.keys(this.value).forEach(locale => {
                formData.append(this.field.attribute + '[' + locale + ']', this.value[locale] || '')
            })
        },

        /**
         * Update the field's internal value.
         */
        handleChange(value) {
            this.value[this.currentLocale] = value
        },

        changeTab(locale, dontEmit) {
            if (this.currentLocale !== locale) {
                if (!dontEmit) {
                    EventBus.$emit('localeChanged', locale);
                }

                this.currentLocale = locale;
            }
        },

        handleTab(e) {
            const currentIndex = this.locales.indexOf(this.currentLocale)
            if (!e.shiftKey) {
                if (currentIndex < this.locales.length - 1) {
                    e.preventDefault();
                    this.changeTab(this.locales[currentIndex + 1]);
                }
            } else {
                if (currentIndex > 0) {
                    e.preventDefault();
                    this.changeTab(this.locales[currentIndex - 1]);
                }
            }
        },

        /*
         * Generate the slug
         */
        generateSlug(value) {
            this.validationErrors = new Errors()
            
            const options = {
                model: this.field.model || null,
                options: this.field.options || null,
                attribute: this.field.attribute || null,
                updating: this.updating,
                initialValue: this.initialValue,
                value,
            }

            this.value[this.currentLocale] = value.toString().toLowerCase()
                .replace(/\s+/g, '-')           // Replace spaces with -
                .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
                .replace(/\-\-+/g, '-')         // Replace multiple - with single -
                .replace(/^-+/, '');            // Trim - from start of text
        },
        
    },

    computed: {
        hasError() {
            return this.validationErrors.has(this.field.attribute)
        },

        firstError() {
            if (this.hasError) {
                return this.validationErrors.first(this.field.attribute)
            }
        }
    },
}
</script>
