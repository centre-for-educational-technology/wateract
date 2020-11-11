<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit spring
            </h2>
        </template>

        <jet-form-section>

            <template #form>
        <div class="py-6">

            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">



                        <div class="flex -mx-2">
                            <div class="w-1/2 px-2">
                                <jet-label class="font-bold" for="title" value="Name" />
                                <jet-input id="title" type="text" class="mt-1 block w-full" v-model="spring.title" />
                            </div>
                        </div>

                    <div class="flex -mx-2">
                        <div class="w-1/2 px-2">
                            <jet-label class="font-bold" for="kkr_code" value="KKR code" />
                            <jet-input id="kkr_code" type="text" class="mt-1 block w-full" v-model="spring.kkr_code" />
                        </div>
                    </div>

                    <div class="flex -mx-2">
                        <div class="w-1/2 px-2">
                            <jet-label class="font-bold" for="latitude" value="$trans('latitude')" />
                            <jet-input id="latitude" type="text" class="mt-1 block w-full" v-model="spring.latitude" />
                            <!--<jet-input-error :message="form.error('latitude')" class="mt-2" />-->
                        </div>

                        <div class="w-1/2 px-2">
                            <jet-label class="font-bold" for="longitude" value="springs.longitude" />
                            <jet-input id="longitude" type="text" class="mt-1 block w-full" v-model="spring.longitude" />
                            <!--<jet-input-error :message="form.error('longitude')" class="mt-2" />-->
                        </div>
                    </div>


                    <div class="col-span-6 sm:col-span-4">
                        <jet-label class="font-bold" for="country" value="Country" />
                        <jet-input id="country" type="text" class="mt-1 block w-full" v-model="spring.country" />
                    </div>
                    <div class="col-span-6 sm:col-span-4">
                        <jet-label class="font-bold" for="county" value="County" />
                        <jet-input id="county" type="text" class="mt-1 block w-full" v-model="spring.county" />
                    </div>
                    <div class="col-span-6 sm:col-span-4">
                        <jet-label class="font-bold" for="settlement" value="Settlement" />
                        <jet-input id="settlement" type="text" class="mt-1 block w-full" v-model="spring.settlement" />
                    </div>




                </div>
            </div>

        </div>
            </template>
            </jet-form-section>
    </app-layout>
</template>


<script>
import AppLayout from './../../Layouts/AppLayout'
import JetFormSection from "./../../Jetstream/FormSection";
import JetInput from "../../Jetstream/Input";
import JetInputError from "../../Jetstream/InputError";
import JetLabel from "../../Jetstream/Label";

export default {
    components: {
        AppLayout,
        JetFormSection,
        JetInput,
        JetInputError,
        JetLabel,
    },
    props: ['spring'],
}
</script>
<!--<script src="./../../../../public/js/map.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=' + services.google.maps.api-key + '&libraries=places&callback=initMap" async defer></script>
-->
