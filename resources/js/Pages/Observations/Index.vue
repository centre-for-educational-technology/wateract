<template>
    <app-layout>
        <template #header>
            <div class="flex w-full">
                <h2 class="w-3/4 font-semibold text-xl text-gray-800 leading-tight">
                    {{ spring.title }}
                </h2>
                <div class="w-1/4" v-if="$page.user">
                    <a href="observations/create" class="border text-xs font-semibold px-4 py-1 leading-normal">
                        Create new observation</a>
                </div>
            </div>
        </template>

        <div class="py-6">

            <div>
                <a :href="'../'+ spring.code">view</a>
                <a :href="'observations'">observations</a>
                <a :href="'measurements'">measurements</a>
            </div>

            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                    <div v-for="observation in spring.observations" :key="observation.id">

                        <div><label><strong>{{ observation.analysis_time }}</strong></label></div>

                    </div>

                </div>

            </div>
        </div>
        </div>

    </app-layout>
</template>
<script>
import AppLayout from './../../Layouts/AppLayout'

export default {
    components: {
        AppLayout,
    },
    props: ['spring'],
}
</script>
