<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Create new observation
            </h2>
        </template>


        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <jet-form-section>

                <template #form>
                </template>
            </jet-form-section>
        </div>
    </app-layout>
</template>
<script>
import AppLayout from './../../Layouts/AppLayout'

export default {
    components: {
        AppLayout,
    },
    props: ['spring','measurements'],
}
</script>
