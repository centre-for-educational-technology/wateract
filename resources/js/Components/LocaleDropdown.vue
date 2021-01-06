<template>
    <div class="dropdown">
        <jet-dropdown width="15">
            <template #trigger>
                <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{ $i18n.locale }}
                </button>
            </template>
            <template #content>
            <div v-for="(locale, index) in locales" :key="index">
                <a class="p-2" href="#" @click.prevent="setLocale(locale)">
                {{ locale }}
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
            locales: ['en', 'et'],
        }
    },
    methods: {
        setLocale(language) {
            this.$store.dispatch('changeLocale', language)
                .then(() => {
                    this.$i18n.locale = language;
                }).catch((error => {
            }));
        }
    }
}
</script>
