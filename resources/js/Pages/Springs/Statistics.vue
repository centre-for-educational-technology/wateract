<template>

    <div class="grid md:grid-cols-3 grid-cols-1 md:gap-10 gap-4">

        <div class="bg-blue-100 p-5">
            <h4>{{ $t('springs.statistics') }}</h4>
            <div class="pt-2">
                <span class="font-bold">{{ $t('springs.number_of_springs') }}:</span>
                {{ statistics.number_of_springs }}
            </div>
            <div class="pt-1">
                <span class="font-bold">{{ $t('springs.number_of_observations') }}:</span>
                {{ statistics.number_of_observations }}
            </div>
            <div class="pt-1">
                <span class="font-bold">{{ $t('springs.number_of_spring_photos') }}:</span>
                {{ statistics.number_of_photos }}
            </div>
            <div class="pt-1">
                <span class="font-bold">{{ $t('springs.new_springs_last_7_days') }}:</span>
                {{ statistics.number_of_springs_7_days }}
            </div>
            <div class="pt-1">
                <span class="font-bold">{{ $t('springs.new_observations_last_7_days') }}:</span>
                {{ statistics.number_of_observations_7_days }}
            </div>
        </div>

        <div class="bg-blue-100 p-5">
            <h4>{{ $t('springs.most_active_users') }}</h4>
            <div class="font-bold pt-2">{{ $t('springs.new_springs') }}</div>
            <div v-for="user in statistics.springs_active_users">
                <div>{{ user.name }}: {{ user.total }}</div>
            </div>
            <div class="font-bold pt-2">{{ $t('springs.new_observations') }}</div>
            <div v-for="user in statistics.observations_active_users">
                <div>{{ user.name }}: {{ user.total }}</div>
            </div>
        </div>

        <div class="bg-blue-100 p-5" v-if="statistics.photo">
            <h4>{{ statistics.photo[0].name || $t('springs.unnamed') }} </h4>
            <div class="pt-2"><a :href="'/springs/'+statistics.photo[0].code">
                <img :src="'/'+statistics.photo[0].thumbnail" /></a></div>
        </div>

    </div>

</template>
<script>
export default {
    data() {
        return {
            statistics: [],
        }
    },
    methods: {
        getStatistics() {
            axios.get('/getStatistics', {}).then(response => {
                this.statistics = response.data;
            })
        },
    },
    created: function(){
        this.getStatistics();
    }
}
</script>
