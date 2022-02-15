<template>

    <div>

        <estonian-maps v-if="this.appCountry === 'ee'" class="px-2 sm:px-5 lg:px-10" :springs="springs" :spring="spring" :view="view" :cluster="cluster" :spring_location="spring_location"></estonian-maps>

        <latvian-map v-if="this.appCountry === 'lv'" class="px-2 sm:px-5 lg:px-10" :springs="springs" :spring="spring" :view="view" :spring_location="spring_location"></latvian-map>

    </div>

</template>
<script>
import EstonianMaps from "./Estonia/EstonianMaps";
import LatvianMap from "./LatvianMap";

export default {
    components: {
      EstonianMaps,
      LatvianMap,
    },
    props: ['springs', 'spring', 'view', 'cluster', 'spring_location'],
    data() {
        return {
            appCountry: process.env.MIX_APP_COUNTRY,
        }
    }
}
</script>
