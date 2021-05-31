<template>
    <app-layout v-if="can('administrate')">
        <template #header>
            <h1>Management</h1>
        </template>

        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">

            <jet-form-section @submitted="importCsv">

                <template #title>
                    CSV File
                </template>

                <template #description>
                    Upload springs .csv file.
                </template>

                <template #form>

                    <jet-label for="csv_file" value=".csv file" />
                    <jet-input id="csv_file" type="file" accept=".csv" class="mt-1 block" v-model="form.csv_file" ref="csv_file" />
                    <jet-input-error :message="form.error('csv_file')" class="mt-2" />

                </template>

                <template #actions>
                    <jet-action-message :on="form.recentlySuccessful" class="mr-3">
                        Saved.
                    </jet-action-message>

                    <jet-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Save
                    </jet-button>
                </template>

            </jet-form-section>

            <jet-section-border />

            <jet-form-section @submitted="fillSpringsAddressFields">

                <template #title>
                    Springs Locations
                </template>

                <template #description>
                    Update springs address fields.
                </template>

                <template #form>

                    Springs to update: {{ springs_to_update.length }}

                </template>

                <template #actions v-if="springs_to_update.length > 0">
                    <jet-action-message :on="form.recentlySuccessful" class="mr-3">
                        Updated.
                    </jet-action-message>

                    <jet-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Update
                    </jet-button>
                </template>

            </jet-form-section>

            <jet-section-border />

            <jet-form-section>

                <template #title>
                    Export Excel Files
                </template>

                <template #description>
                    Download springs and observations .xlsx files.
                </template>

                <template #form>

                    <a target="_blank" href="/admin/exportSprings">Export Springs</a>
                    <br /><br />
                    <a target="_blank" href="/admin/exportObservations">Export Observations</a>

                </template>

            </jet-form-section>

        </div>

    </app-layout>
</template>

<script>
import AppLayout from './../Layouts/AppLayout'
import JetActionMessage from './../Jetstream/ActionMessage'
import JetButton from './../Jetstream/Button'
import JetFormSection from './../Jetstream/FormSection'
import JetSectionBorder from './../Jetstream/SectionBorder'
import JetInput from './../Jetstream/Input'
import JetInputError from './../Jetstream/InputError'
import JetLabel from './../Jetstream/Label'
import { gmapApi } from 'gmap-vue';

export default {
    components: {
        AppLayout,
        JetActionMessage,
        JetButton,
        JetFormSection,
        JetSectionBorder,
        JetInput,
        JetInputError,
        JetLabel,
        gmapApi,
    },
    props: ['springs_to_update'],
    data() {
        return {
            geocoder: null,
            form: this.$inertia.form({
                csv_file: null,
            }, {
                bag: 'importCSV',
            }),
        }
    },
    computed: {
        google: gmapApi,
    },
    mounted() {
        this.$gmapApiPromiseLazy().then(() => {
            if(this.google) {
                this.geocoder = new this.google.maps.Geocoder();
            }
        });
    },
    methods: {
        fillSpringsAddressFields() {
            let geocoder = this.geocoder;
            let to_update = _.take(this.springs_to_update, 100);
            takeABreak(to_update, geocoder);
        },
        importCsv() {
            var input = document.getElementById("csv_file");
            var file = input.files[0];
            var data = new FormData();
            data.append('csv_file', file || '');
            axios.post('/admin/csvfile', data).then(response => {
                console.log(response);
            }).catch(function(error) {
                console.log(error);
            });
        },
    }
}

function takeABreak(springs, geocoder) {
    let spring = springs.shift();
    console.log("Spring: " + spring.id);
    saveSpringLocation(spring, geocoder);
    if (springs.length > 0) {
        setTimeout(function () {
            takeABreak(springs, geocoder);
        }, 3000);
    }
}

function saveSpringLocation(spring, geocoder) {
    let location = {lat: spring.latitude, lng: spring.longitude};
    geocoder.geocode({'latLng': location}, (result, status) => {
        if (status === google.maps.GeocoderStatus.OK) {
            let address_components = result[0].address_components;
            let address = getAddressObject(address_components);
            let data = new FormData();
            data.append('spring_id', spring.id);
            data.append('county', address.county);
            data.append('settlement', address.settlement);
            axios.post('/admin/updateSpringAddress', data).then(response => {
                if (response.statusText === "OK") {
                    console.log("Spring with id " + spring.id + " updated");
                } else {
                    console.log("Not ok: " + spring.id);
                    console.log(response);
                }
            });
        }
    })
}

function getAddressObject(address_components) {
    let ShouldBeComponent = {
        county: [
            "administrative_area_level_1",
            "administrative_area_level_2",
            "administrative_area_level_3",
            "administrative_area_level_4",
            "administrative_area_level_5"
        ],
        settlement: [
            "locality",
            "sublocality",
            "sublocality_level_1",
            "sublocality_level_2",
            "sublocality_level_3",
            "sublocality_level_4"
        ],
        country: ["country"]
    };
    var address = {
        county: "",
        settlement: "",
        country: ""
    };
    address_components.forEach(component => {
        for (var shouldBe in ShouldBeComponent) {
            if (ShouldBeComponent[shouldBe].indexOf(component.types[0]) !== -1) {
                if (shouldBe === "country") {
                    address[shouldBe] = component.short_name;
                } else {
                    address[shouldBe] = component.long_name;
                }
            }
        }
    });
    return address;
}
</script>
