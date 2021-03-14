<template>
    <default-field :field="field" :errors="errors" :show-help-text="showHelpText" full-width-content="true">
        <template slot="field">
            <a 
                class="inline-block font-bold cursor-pointer mr-2 animate-text-color select-none" 
                :class="{ 'text-60': localeKey !== currentLocale, 'text-primary': localeKey === currentLocale }"
                :key="`a-${localeKey}`" 
                v-for="(locale, localeKey) in field.locales"
                @click="changeTab(localeKey)"
            >
                {{ locale }}
            </a>

            <textarea
                ref="field" 
                :id="field.name"
                class="mt-4 w-full form-control form-input form-input-bordered py-3 min-h-textarea"
                :class="errorClasses"
                :placeholder="field.name"
                v-model="value[currentLocale]"
                v-if="!field.singleLine && !field.trix && !field.ckeditor && !field.sluggable && !field.code"
                @keydown.tab="handleTab"
            ></textarea>

            <div v-if="!field.singleField && field.code" class="mt-4">
                <code-field
                    ref="field" 
                    :field="field"
                    :value="value[currentLocale]"
                    @change="handleChange"
                />
            </div>

            <div v-if="!field.singleField && field.trix" class="mt-4">
                <trix
                    ref="field"
                    name="trixman"
                    :value="value[currentLocale]"
                    placeholder=""
                    @change="handleChange"
                />
            </div>

            <div v-if="!field.singleField && field.ckeditor" class="mt-4 w-full">
                <vue-ckeditor
                    ref="field"
                    :id="field.name"
                    v-model="value[currentLocale]"
                    :config="config"
                />

                <p v-if="hasError" class="my-2 text-danger">
                    {{ firstError }}
                </p>
            </div>

            <div v-if="!field.singleField && field.sluggable" class="mt-4 w-full">
                <input
                    ref="field" 
                    :id="field.name"
                    type="text"
                    @keyup="handleKeydown"
                    class="w-full form-control form-input form-input-bordered"
                    :class="errorClasses"
                    :placeholder="field.name"
                    v-model="value[currentLocale]"
                />

                <p v-if="hasError" class="my-2 text-danger">
                    {{ firstError }}
                </p>
            </div>

            <input 
                ref="field" 
                :id="field.name"
                type="text" 
                class="mt-4 w-full form-control form-input form-input-bordered"
                :class="errorClasses"
                :placeholder="field.name"
                v-model="value[currentLocale]"
                v-if="field.singleLine"
                @keydown.tab="handleTab"
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
    import Trix from './Trix/Trix'
    
    import VueCkeditor from 'vue-ckeditor2';

    import Charcounter from './Charcounter/Charcounter';
    
    import CodeField from './Code/FormField';

    import { FormField, HandlesValidationErrors } from 'laravel-nova'

    import { EventBus } from '../event-bus';

    export default {
        mixins: [FormField, HandlesValidationErrors],

        components: { Trix, VueCkeditor, Charcounter, CodeField },

        props: ['resourceName', 'resourceId', 'field'],

        data() {
            return {
                config: this.field.options,
                locales: Object.keys(this.field.locales),
                currentLocale: null,
            }
        },

        mounted() {
            this.currentLocale = this.locales[0] || null;
            EventBus.$on('localeChanged', locale => {
                if (this.currentLocale !== locale) {
                    this.changeTab(locale, true);
                }
            });
        },

        methods: {
            /*
             * Set the initial, internal value for the field.
             */
            setInitialValue() {
                this.value = this.field.value || {}
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
                if (typeof value === "object" && value !== null) {
                    var locale = (value.locale) ? value.locale : this.currentLocale
                    if (this.value && value.value && locale) {
                        this.value[locale] = value.value
                    }
                } else {
                    if (this.value && value) {
                        this.value[locale] = value
                    }
                }
            },

            changeTab(locale, dontEmit) {
                if (this.currentLocale !== locale) {
                    if (!dontEmit) {
                        EventBus.$emit('localeChanged', locale);
                    }

                    this.currentLocale = locale;

                    this.$nextTick(() => {
                        if (this.field.trix || this.field.code) {
                            this.$refs.field.update(this.currentLocale)
                        }
                    })
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

            handleKeydown(event) {
                Nova.$emit('field-update-' + this.slugField, {
                    value: event.target.value
                })
            },
        },

        computed: {
            slugField() {
                return this.field.slug || 'Slug'
            },
        }
    }
</script>
