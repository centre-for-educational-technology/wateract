<template>
    <app-layout>

        <template #header>
            <h1>{{ $t('users.edit_user') }}</h1>
        </template>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <jet-form-section>

                <template #form>

                    <div class="col-span-6 sm:col-span-4">
                        <jet-label for="name" :value="$t('users.name')" />
                        <jet-input id="name" type="text" class="mt-1 block w-full" v-model="form.name" />
                        <jet-input-error :message="form.error('name')" class="mt-2" />
                    </div>

                    <div class="col-span-6 sm:col-span-4">
                        <jet-label for="email" :value="$t('users.email')" />
                        <jet-input id="email" type="email" readonly="true" class="bg-gray-100 mt-1 block w-full" v-model="form.email" />
                        <jet-input-error :message="form.error('email')" class="mt-2" />
                    </div>

                    <div class="col-span-6 sm:col-span-4">
                        <jet-label for="role" :value="$t('users.role')" />
                        <input type="radio" value="" v-model="form.role" /> {{ $t('springs.spring_enthusiast') }}
                        <div v-for="role in roles">
                            <input type="radio" :value="role.name" v-model="form.role"/> {{ $t('springs.'+role.name) }}
                        </div>
                    </div>

                    <div class="col-span-6 sm:col-span-4">
                        <jet-label for="counties" :value="$t('users.counties')" />
                        <multiselect label="name" track-by="id"
                                     :close-on-select="false"
                                     v-model="form.counties"
                                     :options="all_counties"
                                     group-label="country"
                                     group-values="counties"
                                     :group-select="true"
                                     :multiple="true"
                                     :placeholder="$t('users.select_counties')"
                                     :show-labels="false"
                            >
                        </multiselect>
                    </div>

                </template>

                <template #actions>
                    <jet-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing" type="submit" @click.native="updateUser(form)">
                        {{ $t('users.save') }}
                    </jet-button>
                </template>

            </jet-form-section>

            <jet-section-border />

            <delete-user class="mt-10 sm:mt-0" :user_id="form.id"/>

        </div>

    </app-layout>
</template>

<script>
import AppLayout from './../../Layouts/AppLayout'
import JetButton from './../../Jetstream/Button'
import JetDangerButton from './../../Jetstream/DangerButton'
import JetFormSection from './../../Jetstream/FormSection'
import JetSectionBorder from './../../Jetstream/SectionBorder'
import JetInput from './../../Jetstream/Input'
import JetInputError from './../../Jetstream/InputError'
import JetLabel from './../../Jetstream/Label'
import Multiselect from 'vue-multiselect'
import DeleteUser from './DeleteUser'

export default {
    components: {
        AppLayout,
        JetButton,
        JetDangerButton,
        JetFormSection,
        JetSectionBorder,
        JetInput,
        JetInputError,
        JetLabel,
        Multiselect,
        DeleteUser,
    },
    props: ['selected_user', 'roles', 'all_counties', 'user_role', 'user_counties'],
    data() {
        return {
            form: this.$inertia.form({
                id: this.selected_user.id,
                name: this.selected_user.name,
                email: this.selected_user.email,
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
