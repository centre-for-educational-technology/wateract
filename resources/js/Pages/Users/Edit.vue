<template>
    <app-layout>

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit user
            </h2>
        </template>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <jet-form-section>

                <template #form>

                    <div class="col-span-6 sm:col-span-4">
                        <jet-label for="name" value="Name" />
                        <jet-input id="name" type="text" class="mt-1 block w-full" v-model="form.name" />
                        <jet-input-error :message="form.error('name')" class="mt-2" />
                    </div>

                    <div class="col-span-6 sm:col-span-4">
                        <jet-label for="email" value="Email" />
                        <jet-input id="email" type="email" readonly="true" class="bg-gray-100 mt-1 block w-full" v-model="form.email" />
                        <jet-input-error :message="form.error('email')" class="mt-2" />
                    </div>

                    <div class="col-span-6 sm:col-span-4">
                        <jet-label for="role" value="Role" />
                        <input type="radio" value="" v-model="form.role" /> spring enthusiast
                        <div v-for="role in roles">
                            <input type="radio" :value="role.name" v-model="form.role"/> {{ role.name }}
                        </div>
                    </div>

                    <div class="col-span-6 sm:col-span-4">
                        <jet-label for="counties" value="Counties" />
                        <multiselect label="name" track-by="id"
                                     :close-on-select="false"
                                     v-model="form.counties"
                                     :options="all_counties"
                                     group-label="country"
                                     group-values="counties"
                                     :group-select="true"
                                     :multiple="true">
                        </multiselect>
                    </div>

                </template>

                <template #actions>
                    <jet-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing" type="submit" @click.native="updateUser(form)">
                        Save
                    </jet-button>
                </template>

            </jet-form-section>
        </div>

    </app-layout>
</template>

<script>
import AppLayout from './../../Layouts/AppLayout'
import JetButton from './../../Jetstream/Button'
import JetFormSection from './../../Jetstream/FormSection'
import JetInput from './../../Jetstream/Input'
import JetInputError from './../../Jetstream/InputError'
import JetLabel from './../../Jetstream/Label'
import Multiselect from 'vue-multiselect'

export default {
    components: {
        AppLayout,
        JetButton,
        JetFormSection,
        JetInput,
        JetInputError,
        JetLabel,
        Multiselect,
    },
    props: ['user', 'roles', 'all_counties', 'user_role', 'user_counties'],
    data() {
        return {
            form: this.$inertia.form({
                id: this.user.id,
                name: this.user.name,
                email: this.user.email,
                role: this.user_role,
                counties: this.user_counties,
            }, {
                bag: 'updateUser',
            }),
        }
    },

    methods: {
        updateUser: function (data) {
            data._method = 'PUT';
            this.$inertia.post('/admin/users/' + data.id, data)
        },
    },
}
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
