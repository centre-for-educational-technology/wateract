<template>

    <div>

        <estonian-map v-if="this.appCountry === 'ee'" class="px-5 lg:px-10" :springs="springs" :spring="spring" :view="view"></estonian-map>

        <latvian-map v-if="this.appCountry === 'lv'" class="px-5 lg:px-10" :springs="springs" :spring="spring" :view="view"></latvian-map>

    </div>

</template>
<script>
import EstonianMap from "./EstonianMap";
import LatvianMap from "./LatvianMap";

export default {
    components: {
      EstonianMap,
      LatvianMap,
    },
    props: ['springs', 'spring', 'view'],
    data() {
        return {
            appCountry: process.env.MIX_APP_COUNTRY,
        }
    }
}
</script>
