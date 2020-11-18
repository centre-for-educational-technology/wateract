<template>
    <app-layout>
        <template #header>
            <div class="flex w-full">
                <h2 class="w-3/4 font-semibold text-xl text-gray-800 leading-tight">
                    {{ spring.title }}
                </h2>
                <div class="w-1/4" v-if="$page.user">
                    <a href="measurements/create" class="border text-xs font-semibold px-4 py-1 leading-normal">
                        Create new measurement</a>
                </div>
            </div>
        </template>

        <div class="py-6">

            <!--<spring-navigation :spring="spring"></spring-navigation>-->
            <div>
                <a :href="'../'+ spring.code">view</a>
                <a :href="'observations'">observations</a>
                <a :href="'measurements'">measurements</a>
            </div>

            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                    <div v-for="measurement in spring.measurements" :key="measurement.id">

                        <div><label><strong>{{ measurement.analysis_time }}</strong></label></div>
                        <div class="card">

                        <!--<div v-for="measurement->getFieldValues() as $measurement_value)
                        <label><strong>@lang('springs.measurement_fields.'.$measurement_value->name)</strong></label>
                        <div>{{$measurement_value->value}} {{$measurement_value->unit}}</div>
                        @endforeach-->
                        </div>

                    </div>

                    </div>

                </div>
            </div>
        </div>

    </app-layout>
</template>
<script>
import AppLayout from './../../Layouts/AppLayout'
import SpringNavigation from './../../Layouts/SpringNavigation'

export default {
    components: {
        AppLayout,
        SpringNavigation,
    },
    props: ['spring'],
}
</script>
