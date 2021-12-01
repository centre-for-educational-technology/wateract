<template>
    <app-layout>
        <template #header>
            <h1>
                {{ spring.name ||  $t('springs.unnamed')}}
            </h1>
        </template>
        <template #header-buttons>
            <span v-if="$page.user">
                <nav-button v-if="( can_edit || ( spring.status === 'draft' && $page.user.id === spring_creator ) )" :href="'/springs/'+spring.code+'/edit'">{{ $t('springs.edit') }}</nav-button>
                <spring-feedback :spring="spring" />
            </span>
        </template>

        <div class="py-6">

            <spring-navigation :spring="spring" :active_tab="'view'"></spring-navigation>

            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                    <main-map :spring="spring" :view="'show'"></main-map>

                    <div class="sm:flex -mx-2 w-full px-2 py-2">
                        <div class="sm:w-3/4 px-2">

                            <div class="py-2">
                                <strong>{{ $t('springs.description') }}</strong>
                                <div>{{ spring.description }}</div>
                            </div>

                            <div class="py-2" v-if="spring.folklore">
                                <strong>{{ $t('springs.folklore') }}</strong>
                                <div>{{ spring.folklore }}</div>
                            </div>

                            <div class="py-2" v-if="spring.geology">
                                <strong>{{ $t('springs.geology') }}</strong>
                                <div>{{ spring.geology }}</div>
                            </div>

                            <div class="py-2" v-if="spring.references.length > 0">
                                <strong>{{ $t('springs.references') }}</strong>
                                <div v-for="reference in spring.references">
                                    <a target="_blank" v-bind:href="reference.url" >{{ reference.url_title}}</a>
                                </div>
                            </div>

                            <div class="py-2" v-if="spring.database_links.length > 0">
                                <strong>{{ $t('springs.link_with_other_databases') }}</strong>
                                <table class="table-auto text-sm border w-full">
                                    <thead>
                                        <tr class="bg-gray-300 uppercase text-xs">
                                            <th scope="col">{{ $t('springs.database_name') }}</th>
                                            <th scope="col">{{ $t('springs.code') }}</th>
                                            <th scope="col">{{ $t('springs.spring_name') }}</th>
                                            <th scope="col">{{ $t('springs.url') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(database_link, index) in spring.database_links"
                                            :class="{'bg-gray-100': index % 2, 'bg-white': !(index % 2)}">
                                            <td>{{ database_link.database_name }}</td>
                                            <td>{{ database_link.code }}</td>
                                            <td>{{ database_link.spring_name }}</td>
                                            <td><a :href="database_link.url" target="_blank" class="hover:underline">{{ stripUrl(database_link.url) }}</a></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>

                        <div class="sm:w-1/4 px-2">

                            <div class="py-2" v-if="(spring.user && $page.user)">
                                <div class="group">
                                    <strong>{{ $t('springs.added_by') }}</strong>
                                    <div v-if="is('admin') || is('super-admin')">
                                        <a class="underline" :href="'/admin/users/'+spring.user.id">{{ spring.user.name }}</a>
                                    </div>
                                    <div v-else>{{ spring.user.name }}</div>
                                </div>
                            </div>

                            <div class="py-2">
                                <strong>{{ $t('springs.location') }}</strong>

                                <div>{{ $t('springs.countries.'+spring.country) }} {{spring.county}}</div>

                                <div v-if="spring.settlement">{{spring.settlement}}</div>
                            </div>

                            <div class="py-2">
                                <div>{{spring.latitude}} {{spring.longitude}}</div>
                                <a target="_blank" :href="'https://www.google.com/maps/search/?api=1&query='+spring.latitude+'%2C'+spring.longitude">
                                        <div class="underline">{{ $t('springs.navigate') }}</div>
                                </a>
                            </div>

                            <div class="py-2" v-if="spring.kkr_code">
                                <div class="group">
                                    <strong>{{ $t('springs.kkr_code') }}</strong>
                                    <div>{{spring.kkr_code}}</div>
                                </div>
                            </div>

                            <div class="py-2">
                                <div class="group">
                                    <strong>{{  $t('springs.wateract_code') }}</strong>
                                    <div>{{spring.code}}</div>
                                </div>
                            </div>

                            <div class="py-2" v-if="spring.classification">
                                <strong>{{ $t('springs.spring_classification') }}</strong>
                                <div>{{ $t('springs.classification_options.'+spring.classification) }}</div>
                            </div>

                            <div class="py-2" v-if="spring.groundwater_body">
                                <strong>{{ $t('springs.groundwater_body') }}</strong>
                                <div>{{spring.groundwater_body}}</div>
                            </div>

                            <div class="py-2" v-if="spring.ownership">
                                <strong>{{ $t('springs.ownership') }}</strong>
                                <div>{{ $t('springs.ownership_options.'+spring.ownership) }}</div>
                            </div>

                            <div class="py-2" v-if="spring.status">
                                <strong>{{ $t('springs.status') }}</strong>
                                <div v-if="!spring.not_a_spring">{{ $t('springs.status_options.'+spring.status) }}</div>
                                <div v-if="spring.not_a_spring">{{ $t('springs.not_a_spring') }}</div>
                            </div>

                            <div class="py-2" v-if="photos.length > 0">
                                <strong>{{ $t('springs.gallery') }}</strong>
                                <CoolLightBox
                                    :items="photoItems"
                                    :index="index"
                                    :useZoomBar="true"
                                    :slideshow="false"
                                    @close="index = null">
                                </CoolLightBox>

                                <div class="images-wrapper grid grid-cols-2 gap-1">
                                    <div
                                        class="image"
                                        v-for="(image, imageIndex) in photoItems"
                                        :key="imageIndex"
                                        @click="index = imageIndex"
                                    >
                                        <img class="cursor-pointer" :src="image.thumbnail" />
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

        </div>
    </app-layout>
</template>

<script>
import AppLayout from './../../Layouts/AppLayout';
import SpringNavigation from './SpringNavigation';
import JetLabel from '../../Jetstream/Label';
import NavButton from '../../Components/NavButton';
import SpringFeedback from './../SpringFeedback/Create';
import MainMap from './../../Components/Maps/MainMap';
import CoolLightBox from 'vue-cool-lightbox'
import 'vue-cool-lightbox/dist/vue-cool-lightbox.min.css'

export default {
    components: {
        AppLayout,
        SpringNavigation,
        JetLabel,
        NavButton,
        SpringFeedback,
        MainMap,
        CoolLightBox,
    },
    props: ['spring', 'photos', 'can_edit'],
    data() {

        let photoItems = [];
        let added_by_str = this.$i18n.t('springs.added_by');
        let photo_date_str = this.$i18n.t('springs.photo_date');
        _.forEach(this.photos, function(photo) {
            let taken_by = false;
            if (photo.user) {
                taken_by = added_by_str + ': ' + photo.user.name;
            }
            let photo_taken = false;
            if (photo.photo_taken) {
                photo_taken = photo_date_str + ': ' + moment(photo.photo_taken).format("DD.MM.YYYY H:mm");
            }
            photoItems.push({
                src: '/' + photo.path,
                thumbnail: '/' + photo.thumbnail,
                title: photo_taken,
                description: taken_by,
            });
        });

        return {
            dialogVisible: false,
            dialogPhotoUrl: '',
            dialogPhotoUserName: false,
            dialogPhotoDate: false,
            spring_creator: this.spring.user ? this.spring.user.id : null,
            photoItems: photoItems,
            index: null,
        }
    },
    methods: {
        stripUrl(url) {
            if (url) {
                let stripped = url.slice(0, 25);
                return stripped;
            }
            return "";
        },
    },
}

</script>
