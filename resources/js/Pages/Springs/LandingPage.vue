<template>
    <app-layout>

        <template #header>
            <h1>{{ $t('springs.spring_observations_database') }}</h1>
        </template>

        <h2 class="text-center text-xl lg:text-2xl mt-0">{{ $t('springs.collect_all_springs_to_map') }}</h2>

        <div>
            <div class="hidden lg:grid lg:grid-cols-12">
                <div class="col-span-3 ml-28 my-15">
                    <div class="mb-2">
                        <a href="springs" class="cursor-pointer">
                            <img src="/images/est-lat-map.png" /></a>
                    </div>
                    <div class="mb-2 w-full text-sm font-semibold p-2 leading-normal cursor-pointer bg-blue-100" v-if="$page.user">
                        <a href="springs/create">
                            {{ $t('springs.create_new_spring') }}</a>
                    </div>
                    <div class="w-full text-sm font-semibold p-2 leading-normal cursor-pointer bg-blue-100">
                        <a href="springs" >
                            {{ $t('springs.find_spring') }}</a>
                    </div>
                </div>
                <div class="col-span-9 -ml-28">
                    <img src="/images/springs-1.png" />
                </div>
            </div>
            <div class="lg:hidden">
                <img src="/images/springs-1.png" />
            </div>
        </div>

        <div class="mx-10 lg:mx-28">
            <h2 class="mb-2 mt-0">{{ $t('springs.about_project') }}</h2>
            <div>
                {{ $t('springs.about_project_text') }}
            </div>
        </div>

        <div class="my-5 lg:my-10">
            <img src="/images/springs-2.png" />
        </div>

        <div>
            <h1>{{ $t('springs.springs') }}</h1>
            <maps style="height:600px;" :springs="springs"></maps>

            <div class="text-center">
                <div class="py-5">
                    <div class="inline p-2 text-sm font-semibold leading-normal cursor-pointer bg-blue-100 mr-3" v-if="$page.user">
                        <a href="springs/create">
                            {{ $t('springs.create_new_spring') }}</a>
                    </div>
                    <div class="inline p-2 text-sm font-semibold leading-normal cursor-pointer bg-blue-100">
                        <a href="springs">
                            {{ $t('springs.find_spring') }}</a>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <img src="/images/springs-3.png" />
        </div>

        <div class="mx-10 lg:mx-28 lg:-mt-28">
            <h1>{{ $t('springs.about_springs') }}</h1>
            <div class="py-5">
                <h2 class="mt-0">{{ $t('springs.why_study_springs') }}</h2>
                <div>
                    {{ $t('springs.why_study_springs_text') }}
                </div>
                <div class="text-right">
                    <a href="about-springs#why-study-springs">{{ $t('springs.read_more') }}</a>
                </div>
            </div>
            <div>
                <h2>{{ $t('springs.what_is_spring') }}</h2>
                <div>
                    {{ $t('springs.what_is_spring_text') }}
                </div>
                <div class="text-right">
                    <a href="about-springs#what-are-springs">{{ $t('springs.read_more') }}</a>
                </div>
            </div>
        </div>

        <div class="my-5 lg:my-10">
            <img src="/images/springs-4.png" />
        </div>

        <div class="mx-10 lg:mx-28 lg:-mt-10">
            <h1>{{ $t('springs.instructions') }}</h1>
            <div class="grid grid-cols-2 lg:grid-cols-3 gap-10 mt-5">
                <div>
                    <h2>{{ $t('springs.how_to.find_springs') }}</h2>
                    <div>
                        {{ $t('springs.how_to.find_springs_text') }}
                    </div>
                    <div class="text-right">
                        <a href="instructions#how-to-find-springs">{{ $t('springs.read_more') }}</a>
                    </div>
                </div>
                <div>
                    <h2>{{ $t('springs.how_to.recognize_springs') }}</h2>
                    <div>
                        {{ $t('springs.how_to.recognize_springs_text') }}
                    </div>
                    <div class="text-right">
                        <a href="instructions#how-to-recognize-springs">{{ $t('springs.read_more') }}</a>
                    </div>
                </div>
                <div>
                    <h2>{{ $t('springs.how_to.describe_springs') }}</h2>
                    <div>
                        {{ $t('springs.how_to.describe_springs_text') }}
                    </div>
                    <div class="text-right">
                        <a href="instructions#how-to-describe-springs">{{ $t('springs.read_more') }}</a>
                    </div>
                </div>
                <div>
                    <h2>{{ $t('springs.how_to.take_pictures') }}</h2>
                    <div>
                        {{ $t('springs.how_to.take_pictures_text') }}
                    </div>
                    <div class="text-right">
                        <a href="instructions#how-to-take-pictures">{{ $t('springs.read_more') }}</a>
                    </div>
                </div>
                <div>
                    <h2>{{ $t('springs.how_to.assess_water') }}</h2>
                    <div>
                        {{ $t('springs.how_to.assess_water_text') }}
                    </div>
                    <div class="text-right">
                        <a href="instructions#how-to-assess-water">{{ $t('springs.read_more') }}</a>
                    </div>
                </div>
                <div>
                    <h2>{{ $t('springs.how_to.measure_flow_rate') }}</h2>
                    <div>
                        {{ $t('springs.how_to.measure_flow_rate_text') }}
                    </div>
                    <div class="text-right">
                        <a href="instructions#how-to-measure-flow-rate">{{ $t('springs.read_more') }}</a>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <img src="/images/springs-5.png" />
        </div>

        <!--<div class="mx-10 lg:mx-28">
            <h1 class="text-center text-xl sm:text-2xl lg:text-3xl uppercase pb-2">{{ $t('springs.news') }}</h1>
        </div>-->

        <div class="mx-10 lg:mx-28">
            <h1>{{ $t('springs.partners') }}</h1>
            <div class="grid grid-cols-5 gap-6 my-5">
                <img src="/images/logos/okoloogia-est.png" />
                <img src="/images/logos/vpr-eng.png" />
                <img src="/images/logos/keskkonnaministeerium-eng.png" />
                <img src="/images/logos/keskkonnaagentuur-eng.png" />
                <img src="/images/logos/geoloogiateenistus-est.png" />
            </div>
            <div class="grid grid-cols-3 gap-6 my-5">
                <img src="/images/logos/tlu-eng.jpg" />
                <img src="/images/logos/kik-eng.png" />
                <img src="/images/logos/lu-eng.png" />
            </div>
            <div class="grid grid-cols-3 gap-6 my-5">
                <img src="/images/logos/lvgmc-lat.png" />
                <img src="/images/logos/dap-eng.jpg" />
                <img src="/images/logos/interreg-est-lat.jpg" />
            </div>
        </div>

    </app-layout>
</template>

<script>
import AppLayout from './../../Layouts/AppLayout';
import Maps from './Maps';

export default {
    components: {
        Maps,
        AppLayout,
    },
    props: ['springs'],
}
</script>
