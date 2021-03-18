<style>
    p.leading-5 {
        display: none;
    }
</style>
<template>

    <div>

        <h3 class="text-xl">{{ $t('springs.my_observations') }}</h3>
        <data-table
            :columns="columns"
            :url="'/myobservationsview'"
            framework="tailwind"
            order-by="created_at"
            order-dir="desc"
            :pagination="pagination"
            :classes="classes">
            <span slot="filters" slot-scope="{ tableData, perPage }"></span>
        </data-table>

    </div>

</template>
<script>
import ObservationStatusCell from '../../Components/DataTable/ObservationStatusCell';
export default {
    components: {
        ObservationStatusCell,
    },
    data() {
        return {
            columns: [
                {
                    label: this.$i18n.t('springs.spring_name'),
                    name: 'spring.name',
                    orderable: false,
                    transform: ({data}) => `<a class="underline" href="/springs/${data['spring']['code']}">${data['spring']['name'] || this.$i18n.t('springs.unnamed')}</a>`,
                },
                {
                    label: this.$i18n.t('springs.spring_code'),
                    name: 'spring.code',
                    orderable: false,
                    transform: ({data}) => `${data['spring']['code']}`,
                },
                {
                    label: this.$i18n.t('springs.measurement_time'),
                    name: 'measurement_time',
                    orderable: true,
                    transform: ({data, name}) => `${moment(data[name]).format("DD.MM.YYYY H:mm")}`,
                },
                {
                    label: this.$i18n.t('springs.created_at'),
                    name: 'created_at',
                    orderable: true,
                    transform: ({data, name}) => `${moment(data[name]).format("DD.MM.YYYY H:mm")}`,
                },
                {
                    label: this.$i18n.t('springs.status'),
                    name: 'status',
                    orderable: true,
                    component: ObservationStatusCell,
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
}
</script>
