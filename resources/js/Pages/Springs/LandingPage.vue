<template>
    <app-layout>

        <template #header>
            <h1>{{ $t('springs.spring_observations_database') }}</h1>
        </template>

        <h2 class="text-center text-xl lg:text-2xl mt-0">{{ $t('springs.springs_slogan') }}</h2>

        <div>
            <div class="hidden lg:grid lg:grid-cols-12">
                <div class="col-span-3 ml-28 my-15 z-10">
                    <div class="mb-2">
                        <a target="_blank" :href="'https://allikad.info/manuals/allikad.info_manual_'+ this.locales[this.$i18n.locale] +'.pdf'"
                           class="border-red-500 cursor-pointer">
                            <img :src="'/images/manuals/allikad.info_manual_'+ this.$i18n.locale + '.png'" /></a>
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

        <div class="mx-5 sm:mx-10 lg:mx-28">
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

            <main-map class="sm:px-5 lg:px-10" :springs="springs" ></main-map>

            <statistics class="mt-5 px-2 sm:px-5 lg:px-10" style="max-width:1120px; margin:20px auto"></statistics>

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

        <div class="mx-5 sm:mx-10 lg:mx-28 lg:-mt-16">
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

        <div class="mx-5 sm:mx-10 lg:mx-28 lg:-mt-10">
            <h1>{{ $t('springs.instructions') }}</h1>
            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-10 mt-5">
                <div>
                    <img class="mx-auto w-3/5" src="/images/icons/how-to-find.png" />
                    <h2>{{ $t('springs.how_to.find_springs') }}</h2>
                    <div>
                        {{ $t('springs.how_to.find_springs_text') }}
                    </div>
                    <div class="text-right">
                        <a href="instructions#how-to-find-springs">{{ $t('springs.read_more') }}</a>
                    </div>
                </div>
                <div>
                    <img class="mx-auto w-3/5" src="/images/icons/how-to-recognize.png" />
                    <h2>{{ $t('springs.how_to.recognize_springs') }}</h2>
                    <div>
                        {{ $t('springs.how_to.recognize_springs_text') }}
                    </div>
                    <div class="text-right">
                        <a href="instructions#how-to-recognize-springs">{{ $t('springs.read_more') }}</a>
                    </div>
                </div>
                <div>
                    <img class="mx-auto w-3/5" src="/images/icons/how-to-describe.png" />
                    <h2>{{ $t('springs.how_to.describe_springs') }}</h2>
                    <div>
                        {{ $t('springs.how_to.describe_springs_text') }}
                    </div>
                    <div class="text-right">
                        <a href="instructions#how-to-describe-springs">{{ $t('springs.read_more') }}</a>
                    </div>
                </div>
                <div>
                    <img class="mx-auto w-3/5" src="/images/icons/how-to-take-pictures.png" />
                    <h2>{{ $t('springs.how_to.take_pictures') }}</h2>
                    <div>
                        {{ $t('springs.how_to.take_pictures_text') }}
                    </div>
                    <div class="text-right">
                        <a href="instructions#how-to-take-pictures">{{ $t('springs.read_more') }}</a>
                    </div>
                </div>
                <div>
                    <img class="mx-auto w-3/5" src="/images/icons/how-to-assess-water.png" />
                    <h2>{{ $t('springs.how_to.assess_water') }}</h2>
                    <div>
                        {{ $t('springs.how_to.assess_water_text') }}
                    </div>
                    <div class="text-right">
                        <a href="instructions#how-to-assess-water">{{ $t('springs.read_more') }}</a>
                    </div>
                </div>
                <div>
                    <img class="mx-auto w-3/5" src="/images/icons/how-to-measure-flow-rate.png" />
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

        <div class="sm:mx-10 lg:mx-28">
            <h1 class="mb-2 sm:mb-5">{{ $t('springs.partners') }}</h1>
            <img src="/images/logos.jpg" />
        </div>

        <div class="mt-5 sm:mt-10 mx-5 sm:mx-10 lg:mx-28">
            <h1 class="mb-2 sm:mb-5">{{ $t('springs.supporters') }}</h1>
            <div class="flex justify-center">
                <div class="inline-block mr-5">
                    <a target="_blank" href="https://www.ohtuleht.ee/">
                        <img alt="ohtuleht" src="/images/supporters/ohtuleht.png" /></a>
                </div>
                <div class="inline-block mr-5">
                    <a target="_blank" href="https://www.ekool.eu/">
                        <img alt="ekool" src="/images/supporters/ekool.png" /></a>
                </div>
                <div class="inline-block">
                    <a target="_blank" href="https://developers.kartes.lv/">
                        <img alt="Jāņa sēta" src="/images/supporters/kijs.png" /></a>
                </div>
            </div>
        </div>

    </app-layout>
</template>

<script>
import AppLayout from './../../Layouts/AppLayout';
import MainMap from './../../Components/Maps/MainMap';
import Statistics from './Statistics';

export default {
    components: {
        AppLayout,
        MainMap,
        Statistics,
    },
    props: ['springs'],
    data() {
        return {
            locales: {
                'en': 'ENG',
                'et': 'EST',
                'lv': 'LAV',
                'ru': 'RUS',
                'fr': 'ENG',
            },
        }
    },
}
</script>
