<style>
p.leading-5 {
    display: none;
}
.news-content a {
    text-decoration: underline;
}
</style>
<template>
    <app-layout>
        <template #header>
            <h1>{{ $t('springs.news') }}</h1>
        </template>
        <template #header-buttons>
            <span v-if="$page.user && is('admin')">
                <nav-button :href="'news/create'">{{ $t('springs.add_new_news') }}</nav-button>
            </span>
        </template>

            <div class="max-w-7xl mx-auto lg:py-10 sm:px-6 lg:px-8">
                <div class="p-5 sm:p-10 bg-white overflow-hidden shadow-xl sm:rounded-lg">

                    <div v-for="news_item in news.data" :key="news_item.id">

                        <div v-if="$page.user && is('admin')">
                            <nav-button class="float-right"
                                        :href="'/news/'+news_item.id+'/edit'">
                                {{ $t('springs.edit') }}</nav-button>
                        </div>

                        <h2 class="mt-5 sm:mt-10">
                            {{ news_item.title }}
                        </h2>

                        <!--<div>{{ moment(news_item.created_at).format("DD.MM.YYYY H:mm") }}</div>-->

                        <div class="news-content pt-5" v-html="news_item.body_text"></div>

                        <jet-section-border />

                    </div>

                    <tailable-pagination v-show="news.data.length > 0" :size="'small'" :data="news" :showNumbers="true" @page-changed="getLatestNews"></tailable-pagination>

                </div>
            </div>

    </app-layout>
</template>
<script>
import AppLayout from './../../Layouts/AppLayout'
import JetInput from "../../Jetstream/Input";
import JetSecondaryButton from './../../Jetstream/SecondaryButton'
import JetLabel from "../../Jetstream/Label";
import NavButton from '../../Components/NavButton';
import JetSectionBorder from './../../Jetstream/SectionBorder'

export default {
    components: {
        AppLayout,
        JetInput,
        JetSecondaryButton,
        JetLabel,
        NavButton,
        JetSectionBorder,
    },
    props: [],
    data() {
        return {
            news: {"data": []},
        }
    },
    methods: {
        getLatestNews(page = 1) {
            let params = {
                "locale": this.$i18n.locale,
                "order_by": "created_at",
                "page": page,
            }
            axios.get('/getNews', { params }).then(response => {
                this.news = response.data;
            })
        },
    },
    created: function(){
        this.getLatestNews();
    }
}
</script>
