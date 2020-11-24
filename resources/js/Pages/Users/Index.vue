<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Users
            </h2>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

        <table class="table-auto">
            <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Roles</th>
                <th></th>
            </tr>
            </thead>
            <tr v-for="row in data">
                <td>{{ row.name }}</td>
                <td>{{ row.email }}</td>
                <td>
                    <!--<label v-for="role in row.getRoleNames()">{{ role }}</label>-->
                </td>
                <td width="130">
                    <button @click="edit(row)" class="text-white bg-blue-500 border text-xs font-semibold px-4 py-1 leading-normal">Edit</button>
                    <button @click="deleteRow(row)" class="text-white bg-red-500 border text-xs font-semibold px-4 py-1 leading-normal">Delete</button>
                </td>
            </tr>
        </table>

                </div>
            </div>
        </div>

        <div class="modal fade" id="modal">
            <div class="modal-dialog">

                <div class="modal-content">
                    <!--<div class="modal-header">
                        <h4 class="modal-title">New Contact</h4>
                    </div>-->
                    <div class="modal-body">

                        <div class="form-group">
                            <label for="name">Name</label>
                            <input class="form-control" required id="name" v-model="form.name"/>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input class="form-control" required id="email" v-model="form.email"/>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" @click="closeModal()">Close</button>
                        <button type="submit" class="btn btn-primary" v-show="!editMode" @click="save(form)">Save
                        </button>
                        <button type="submit" class="btn btn-primary" v-show="editMode" @click="update(form)">Update
                        </button>
                    </div>
                </div><!-- /.modal-content -->

            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

    </app-layout>
</template>

<script>
import AppLayout from "../../Layouts/AppLayout";

export default {
    components: {
        AppLayout,
    },
    props: ['data'],
    data() {
        return {
            editMode: false,
            form: {
                name: null,
                phone: null,
            },
        }
    },
    methods: {
        openModal: function () {
            $('#modal').modal('show')
        },
        closeModal: function () {
            $('#modal').modal('hide')
            this.reset();
            this.editMode=false;
        },
        reset: function () {
            this.form = {
                name: null,
                email: null,
            }
        },
        save: function (data) {
            this.$inertia.post('/admin/contacts', data)
            this.reset();
            this.closeModal();
            this.editMode = false;
        },
        edit: function (data) {
            this.form = Object.assign({}, data);
            this.editMode = true;
            this.openModal();
        },
        update: function (data) {
            if (!confirm('Sure')) return;
            data._method = 'PUT';
            this.$inertia.post('/admin/users/' + data.id, data)
            this.reset();
            this.closeModal();
        },
        deleteRow: function (data) {
            if (!confirm('Sure')) return;
            data._method = 'DELETE';
            this.$inertia.post('/admin/users/' + data.id, data)
            this.reset();
            this.closeModal();
        }
    }
}
</script>
