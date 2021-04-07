<template>
    <div class="dropdown">
        <jet-dropdown width="15">
            <template #trigger>
                <button class="btn dropdown-toggle p-2" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{ locales[$i18n.locale] }}
                </button>
            </template>
            <template #content>
            <div v-for="(language, locale) in locales" :key="locale">
                <a class="p-2" href="#" @click.prevent="setLocale(locale)">
                {{ language }}
            </a></div>
            </template>
        </jet-dropdown>
    </div>

</template>

<script>
import JetDropdown from './../Jetstream/Dropdown'
import JetDropdownLink from './../Jetstream/DropdownLink'
export default {
    components: {
        JetDropdown,
        JetDropdownLink,
    },
    data() {
        return {
            locales: {
                'en': 'ENG',
                'et': 'EST',
                'lv': 'LAV',
                'ru': 'RUS',
            },
        }
    },
    methods: {
        setLocale(language) {
            this.$store.dispatch('changeLocale', language)
                .then(() => {
                    this.$i18n.locale = language;
                    axios.get('/locale/'+language);
                    var page_url = window.location.href;
                    if ( page_url.endsWith("news") ) {
                        window.location.reload(true)
                    }
                }).catch((error => {
            }));
        }
    }
}
</script>
