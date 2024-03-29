<style>
    .whitespace-no-wrap {
        white-space: normal;
    }
    .laravel-vue-datatable-td {
        vertical-align: top;
    }
    p.leading-5 {
        display: none;
    }
</style>
<template>
    <app-layout>
        <template #header>
            <h1>
                {{ spring.name ||  $t('springs.unnamed')}}
            </h1>
        </template>

        <div class="py-6">

            <spring-navigation :spring="spring" :active_tab="'feedback'"></spring-navigation>

            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="px-4 py-5 bg-white sm:p-6 flex">

                        <div v-if="spring.feedback.length === 0">{{ $t('springs.no_feedback_added') }}</div>

                        <data-table v-if="spring.feedback.length > 0"
                            :key="tableRefresh"
                            :columns="columns"
                            :url="'/springs/'+spring.id+'/feedbackview'"
                            framework="tailwind"
                            order-by="created_at"
                            order-dir="desc"
                            :pagination="pagination"
                            :classes="classes">
                            <span slot="filters" slot-scope="{ tableData, perPage }"></span>
                        </data-table>

                    </div>

                </div>
            </div>

        </div>

    </app-layout>
</template>
<script>
import AppLayout from './../../Layouts/AppLayout'
import SpringNavigation from '../Springs/SpringNavigation'
import JetLabel from "../../Jetstream/Label"
import DeleteButton from "../../Components/DataTable/DeleteButton"

export default {
    components: {
        AppLayout,
        SpringNavigation,
        JetLabel,
    },
    props: ['spring', 'can_edit'],
    data() {
        return {
            tableRefresh: 0,
            columns: [
                {
                    label: this.$i18n.t('springs.date_time'),
                    name: 'created_at',
                    orderable: true,
                    transform: ({data, name}) => `${moment(data[name]).format("DD.MM.YYYY H:mm")}`,
                },
                {
                    label: this.$i18n.t('springs.added_by'),
                    name: 'user.name',
                    columnName: 'user.name',
                    orderable: false,
                },
                {
                    label: this.$i18n.t('springs.spring_name'),
                    name: 'spring_name',
                    orderable: false,
                },
                {
                    label: this.$i18n.t('springs.feedback'),
                    width: 300,
                    name: 'feedback',
                    orderable: false,
                },
                {
                    label: this.$i18n.t('springs.location'),
                    name: 'location',
                    orderable: false,
                    transform: ({data}) => `${data['latitude']}, ${data['longitude']}`,
                },
                {
                    label: '',
                    meta: {
                        can_edit: this.can_edit
                    },
                    orderable: false,
                    classes: {
                        'btn': true,
                        'btn-sm': true,
                    },
                    event: "click",
                    handler: this.deleteRow,
                    component: DeleteButton,
                },
            ],
            pagination: {
                'size': 'small',
            },
            classes: {
                "td": {
                    'py-4': true,
                    'px-6': true,
                    'border-b': true,
                    'border-grey-light': true,
                    'text-gray-light': true,
                },
                "th": {
                    'text-gray-dark': true,
                    'border-gray': true,
                    'border-b-2': true,
                    'border-t-2': true,
                    'border-gray-200': true,
                    'py-3': true,
                    'px-4': true,
                    'bg-gray-100': true,
                    'text-left': true,
                    'text-xs': true,
                    'font-semibold': true,
                    'uppercase': true,
                },
            }
        }
    },
    methods: {
        deleteRow(data) {
            if (confirm(this.$i18n.t('springs.delete_feedback_confirmation'))) {
                let form = this.$inertia.form({
                    '_method': 'DELETE',
                    feedback_id: data.id,
                }, {
                    bag: 'deleteFeedback'
                });
                form.post('/spring_feedback/' + data.id, {
                    preserveScroll: false
                }).then(response => {
                    this.tableRefresh++;
                })
            }
        },
    },
}
</script>
