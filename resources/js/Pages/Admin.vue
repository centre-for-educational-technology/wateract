<template>
    <app-layout v-if="can('administrate')">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Management
            </h2>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

        <jet-form-section @submitted="importCsv">

            <template #form>

                    <jet-label for="csv_file" value="Upload csv file" />
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

                </div>
            </div>
        </div>



    </app-layout>
</template>

<script>
import AppLayout from './../Layouts/AppLayout'
import JetActionMessage from './../Jetstream/ActionMessage'
import JetButton from './../Jetstream/Button'
import JetFormSection from './../Jetstream/FormSection'
import JetInput from './../Jetstream/Input'
import JetInputError from './../Jetstream/InputError'
import JetLabel from './../Jetstream/Label'


export default {
    components: {
        AppLayout,
        JetActionMessage,
        JetButton,
        JetFormSection,
        JetInput,
        JetInputError,
        JetLabel,
    },
    props: [],
    data() {
        return {
            form: this.$inertia.form({
                csv_file: null,
            }, {
                bag: 'importCSV',
            }),
        }
    },
    methods: {
        importCsv() {
            var input = document.getElementById("csv_file");
            var file = input.files[0];
            var data = new FormData();
            data.append('csv_file', file || '');
            axios.post('/admin/csvfile', data).then(response => {
                console.log(response);
            });


            /*meelvar fReader = new FileReader();
            var fileContent;
            fReader.readAsDataURL(input.files[0]);
            fReader.onloadend = function(event){
                fileContent = event.target.result;
                console.log(fileContent);
                var data = new FormData();
                data.append('csv_file', fileContent || '');
                axios.post('/admin/csvfile', data).then(response => {
                    console.log(response);
                });
            }meel*/

            /*this.form.post('/admin/csvfile', {
                preserveScroll: true
            }).then(() => {
                this.$refs.csv_file.focus()
            })*/


            /*axios.post('/admin/csvfile', this.form.csv_file, { headers: {
                    'Content-Type': 'multipart/form-data'
                }}).then(response => {
                console.log(response);
            });*/


            //var data = new FormData();
            //data.append('csv_file', this.form.csv_file.raw || '');



        },

    }
}
</script>
