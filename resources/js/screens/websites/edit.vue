<script type="text/ecmascript">

    import SEOModal from './../../components/SEOModal';

    export default {
        components: {
            'seo-modal': SEOModal,
        },

        data() {
            return {
                entry: null,
                ready: false,

                id: this.$route.params.id || 'new',

                seoModalShown: false,

                form: {
                    errors: [],
                    working: false,
                    id: '',
                    name: '',
                    domain: '',
                    meta: {
                        meta_description: '',
                        opengraph_title: '',
                        opengraph_description: '',
                        opengraph_image: '',
                        opengraph_image_width: '',
                        opengraph_image_height: '',
                        twitter_title: '',
                        twitter_description: '',
                        twitter_image: '',
                    }
                }
            };
        },

        /**
         * Prepare the component.
         */
        mounted() {
            document.title = "Website — Wink.";

            this.http().get('/api/websites/' + this.id).then(response => {
                this.entry = response.data.entry;

                this.form.id = response.data.entry.id;

                if (this.id != 'new') {
                    this.form.name = response.data.entry.name;
                    this.form.domain = response.data.entry.domain;

                    this.form.meta = {
                        meta_description: response.data.entry.meta.meta_description || '',
                        opengraph_title: response.data.entry.meta.opengraph_title || '',
                        opengraph_description: response.data.entry.meta.opengraph_description || '',
                        opengraph_image: response.data.entry.meta.opengraph_image || '',
                        opengraph_image_width: response.data.entry.meta.opengraph_image_width || '',
                        opengraph_image_height: response.data.entry.meta.opengraph_image_height || '',
                        twitter_title: response.data.entry.meta.twitter_title || '',
                        twitter_description: response.data.entry.meta.twitter_description || '',
                        twitter_image: response.data.entry.meta.twitter_image || '',
                    }
                }

                this.ready = true;
            }).catch(error => {
                this.ready = true;
            });
        },


        // watch: {
        //     'form.slug'(val) {
        //         this.debouncer(() => {
        //             this.form.slug = this.slugify(val);
        //         });
        //     },

        //     'form.name'(val) {
        //         this.debouncer(() => {
        //             if (this.form.slug) return;

        //             this.form.slug = this.slugify(val);
        //         });
        //     },
        // },


        methods: {
            /**
             * Delete the Website.
             */
            deleteWebsite() {
                this.alertConfirm("Are you sure you want to delete this website?", () => {
                    this.http().delete('/api/websites/' + this.id, this.form).then(response => {
                        this.$router.push({name: 'websites'})
                    })
                });
            },


            /**
             * Save the Website.
             */
            save() {
                this.form.working = true;
                this.form.errors = [];

                this.http().post('/api/websites/' + this.id, this.form).then(response => {
                    this.form.working = false;

                    this.notifySuccess('Saved!', 2000);
                }).catch(error => {
                    this.form.errors = error.response.data.errors;

                    this.form.working = false;
                });
            },


            /**
             * Open the SEO & Social modal.
             */
            seoModal() {
                this.seoModalShown = true;
            },

            /**
             * Close the SEO modal.
             */
            closeSeoModal({content}) {
                this.seoModalShown = false;
                this.form.meta = content;
            },
        }
    }
</script>

<template>
    <div>
        <page-header>
            <div class="flex items-center" v-if="ready && entry" slot="right-side">
                <button class="py-1 px-2 btn-primary text-sm mr-6" @click="save" v-loading="form.working">Save</button>

                <dropdown class="relative">
                    <button slot="trigger" class="focus:outline-none text-light hover:text-primary h-8">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="w-4 h-4 fill-current mt-1">
                            <path d="M17 16v4h-2v-4h-2v-3h6v3h-2zM1 9h6v3H1V9zm6-4h6v3H7V5zM3 0h2v8H3V0zm12 0h2v12h-2V0zM9 0h2v4H9V0zM3 12h2v8H3v-8zm6-4h2v12H9V8z"/>
                        </svg>
                    </button>

                    <div slot="content" class="dropdown-content pin-r min-w-dropdown mt-1 text-sm py-2">
                        <a href="#" @click.prevent="seoModal" class="no-underline text-text-color hover:text-primary w-full block py-2 px-4">
                            SEO & Social
                        </a>
                        <a href="#" @click.prevent="deleteWebsite" class="no-underline text-red w-full block py-2 px-4" v-if="id != 'new'">Delete</a>
                    </div>
                </dropdown>
            </div>
        </page-header>

        <div class="container">
            <preloader v-if="!ready"></preloader>

            <h2 v-if="ready && !entry" class="text-center font-normal">
                404 — Website not found
            </h2>

            <div class="lg:w-2/3 mx-auto" v-if="ready && entry">
                <h1 class="font-semibold text-3xl mb-10" v-if="id != 'new'">Edit Website</h1>
                <h1 class="font-semibold text-3xl mb-10" v-else>New Website</h1>

                <div class="input-group">
                    <label for="name" class="input-label">Website Name</label>
                    <input type="text" class="input"
                           v-model="form.name"
                           placeholder="Give me a name"
                           id="name">

                    <form-errors :errors="form.errors.name"></form-errors>
                </div>

                <div class="input-group">
                    <label for="name" class="input-label">Website Domain</label>
                    <input type="text" class="input"
                           v-model="form.domain"
                           placeholder="enter your domain please"
                           id="domain">

                    <form-errors :errors="form.errors.slug"></form-errors>
                </div>
            </div>
        </div>


        <!-- SEO & Social Modal -->
        <seo-modal v-if="seoModalShown"
                   :input="form.meta"
                   @close="closeSeoModal"></seo-modal>
    </div>
</template>
