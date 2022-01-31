<template>

    <div>

        <div v-if="spring.featured_photos.length > 0">
            <a :href="'/springs/'+spring.code">
                <img :src="'/'+spring.featured_photos[0].thumbnail" /></a>
        </div>

        <div v-else-if="spring.all_photos.length > 0">
            <a :href="'/springs/'+spring.code">
                <img :src="'/'+spring.all_photos[0].thumbnail" /></a>
        </div>

        <div v-else>
            <a :href="'/springs/'+spring.code">
                <img src="/images/spring-default-image.jpg" /></a>
        </div>

        <div class="text-sm px-3 py-2">
            <div class="font-bold">
                <a class="text-base" :href="'/springs/'+spring.code">{{ spring.name || $t('springs.unnamed') }}</a>
            </div>
            <div>
                <span v-if="spring.country_info">{{ $t('springs.countries.'+spring.country_info.code) }}</span>
                <span v-if="!spring.country_info">{{ spring.country }}</span>
                {{ spring.county }}
                {{ spring.settlement }}

            </div>
            <div v-if="spring.classification">
                <jet-label class="font-bold">{{ $t('springs.classification')}}</jet-label>
                <div>{{ $t('springs.classification_options.'+spring.classification) }}</div>
            </div>
            <div v-if="spring.status">
                <jet-label class="font-bold">{{  $t('springs.status') }}</jet-label>
                <div>{{ $t('springs.status_options.'+spring.status) }}</div>
            </div>
        </div>

    </div>

</template>
<script>
import JetLabel from "../../Jetstream/Label";
export default {
    components: {
        JetLabel,
    },
    props: ['spring'],
}
</script>
