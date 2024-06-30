<x-admin::layouts>
    <!-- Title of the page. -->
    <x-slot:title>
        @lang('admin::app.configuration.index.title')
    </x-slot>

    <div class="mb-7 items-center justify-between">
        <v-configuration-search>

        </v-configuration-search>
    </div>

    @pushOnce('scripts')
        <script type="text/x-template" id="v-configuration-search-template">
            <div class="relative mb-7 w-[525px] max-w-[525px] items-center max-lg:w-[400px] ltr:ml-2.5 rtl:mr-2.5">
                <i class="icon-search absolute top-1.5 flex items-center text-2xl ltr:left-3 rtl:right-3"></i>

                <input 
                    type="text"
                    class="peer block w-full rounded-lg border bg-white px-10 py-1.5 leading-6 text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"
                    :class="{'border-gray-400': isDropdownOpen}"
                    placeholder="https://google.com" {{--  @lang('admin::app.configuration.index.search')"  --}}
                    v-model.lazy="searchTerm"
                    @click="searchTerm.length >= 2 ? isDropdownOpen = true : {}"
                    v-debounce="500"
                >
            </div>
            <div 
                v-if="idDataReady"
                class="relative mb-7 w-[525px] max-w-[525px] items-center max-lg:w-[400px] ltr:ml-2.5 rtl:mr-2.5"
            >
                <div class="mb-5">
                    <h1 class="font-bold text-2xl">PageSpeed Insights API Demo</h1>
                    <span>@{{searchedResults.id}}</span>
                </div>
                <div class="mb-5">
                    <h1 class="font-bold text-2xl">Chrome User Experience Report Result</h1>
                    <div>
                        <span>First Contentful Paint: </span>
                        <span>@{{searchedResults.loadingExperience.metrics.FIRST_CONTENTFUL_PAINT_MS.category}}</span>
                    </div>
                    <div>
                        <span>First input Delay: </span>
                        <span>@{{searchedResults.loadingExperience.metrics.FIRST_INPUT_DELAY_MS.category}}</span>
                    </div>
                </div>
                <div class="mb-5">
                    <h1 class="font-bold text-2xl">LightHouse Result</h1>
                    <div>
                        <span>First Contentful Paint: </span>
                        <span>@{{searchedResults.lighthouseResult.audits['first-contentful-paint'].displayValue}}</span>
                    </div>
                    <div>
                        <span>Speed Index: </span>
                        <span>@{{searchedResults.lighthouseResult.audits['speed-index'].displayValue}}</span>
                    </div>
                    <div>
                        <span>Time To Interactive: </span>
                        <span>@{{searchedResults.lighthouseResult.audits.interactive.displayValue}}</span>
                    </div>
                    <div>
                        <span>First Meaningful Paint: </span>
                        <span>@{{searchedResults.lighthouseResult.audits['first-meaningful-paint'].displayValue}}</span>
                    </div>
                    <div>
                        <span>Estimated Input Latency: </span>
                        <span>@{{searchedResults.lighthouseResult.audits['network-server-latency'].displayValue}}</span>
                    </div>
                </div>
            </div>
            <div v-else-if="isError">Something went wrong</div>
            <div v-else-if="searchTerm"> Loading . . . </div>
        </script>

        <script type="module">
            app.component('v-configuration-search', {
                template: '#v-configuration-search-template',
                
                data() {
                    return {
                        idDataReady: false,

                        searchTerm: '',

                        searchedResults: [],

                        isError: false
                    };
                },

                watch: {
                    searchTerm(newVal, oldVal) {
                        this.search();
                    },
                },

                methods: {
                    search() {
                        if (this.searchTerm.length <= 1) {
                            this.searchedResults = [];

                            return;
                        }

                        this.idDataReady = false;
                        this.isError = false;
                        
                        this.$axios.get("{{ route('admin.google.fetch-data') }}", {
                                params: {query: this.searchTerm}
                            })
                            .then((response) => {
                                this.searchedResults = response.data.data;

                                this.idDataReady = true;
                            })
                            .catch((error) => {
                                this.isError = true
                                console.log(error);
                            });
                    },
                },
            });
        </script>
    @endpushOnce
</x-admin::layouts>