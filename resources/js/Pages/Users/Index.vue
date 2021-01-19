<template>
    <app-layout>
        <template #header>
            <h1>
                {{ $t('springs.users') }}
            </h1>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="px-4 py-5 bg-white sm:p-6">

                    <table class="table-auto" style="width:100%">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(row, index) in users" :class="{'bg-gray-100': index % 2, 'bg-white': !(index % 2)}">
                                <td>{{ row.name }}</td>
                                <td>{{ row.email }}</td>
                                <td>
                                    <label v-for="role in row.roles">{{ role.name }}</label>
                                </td>
                                <td class="float-right">
                                    <inertia-link class="block pl-3 pr-4 py-2 border bg-white uppercase text-xs hover:text-gray-500 rounded-md" :href="'/admin/users/'+row.id+'/edit'">Edit</inertia-link>
                                    <!--<button @click="deleteRow(row)" class="text-white bg-red-500 border text-xs font-semibold px-4 py-1 leading-normal">Delete</button-->
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    </div>
                </div>
            </div>
        </div>

    </app-layout>
</template>

<script>
import AppLayout from "../../Layouts/AppLayout";

export default {
    components: {
        AppLayout,
    },
    props: ['users'],
}
</script>
