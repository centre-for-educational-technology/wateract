<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <!--<welcome />-->

                    <table class="table-auto">
                        <thead>
                        <tr>
                            <th>Title</th>
                            <th>Description</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tr v-for="spring in springs">
                            <td>{{ spring.title }}</td>
                            <td>{{ spring.description }}</td>
                            <td width="130">
                                <a class="text-white bg-blue-500 border text-xs font-semibold px-4 py-1 leading-normal" :href="'springs/'+spring.id">springs.view</a>
                            </td>
                        </tr>
                    </table>

                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from './../Layouts/AppLayout'

    export default {
        components: {
            AppLayout,
        },
        props: ['springs'],
    }
</script>
