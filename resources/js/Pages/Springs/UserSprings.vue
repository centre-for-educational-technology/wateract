<style>
    .whitespace-no-wrap {
        white-space: normal;
    }
    p.leading-5 {
        display: none;
    }
</style>
<template>

    <div>

        <h3 class="text-xl">{{ $t('springs.my_springs') }}</h3>

        <data-table
            :columns="columns"
            :translate="translate"
            :url="'/userspringsview/'+user_id"
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
import NavButton from '../../Components/NavButton';

export default {
    components: {
        NavButton,
    },
    props: ['user_id'],
    data() {
        return {
            columns: [
                {
                    label: this.$i18n.t('springs.name'),
                    name: 'name',
                    orderable: true,
                    transform: ({data}) => `<a class="underline" href="/springs/${data['code']}">${data['name'] || this.$i18n.t('springs.unnamed')}</a>`,
                },
                {
                    label: this.$i18n.t('springs.location'),
                    name: 'country',
                    orderable: true,
                    transform: ({data}) => `${this.$i18n.t('springs.countries.'+data['country'])}${data['county'] ? ', ' + data['county'] : ''}${data['settlement'] ? ', ' + data['settlement'] : ''}`,
                },
                {
                    label: this.$i18n.t('springs.date_time'),
                    name: 'created_at',
                    orderable: true,
                    transform: ({data, name}) => `${moment(data[name]).format("DD.MM.YYYY H:mm")}`,
                },
                {
                    label: this.$i18n.t('springs.code'),
                    name: 'code',
                    orderable: true,
                    transform: ({data}) => `<a class="underline" href="/springs/${data['code']}">${data['code']}</a>`,
                },
                {
                    label: this.$i18n.t('springs.status'),
                    name: 'status',
                    orderable: true,
                    transform: ({data, name}) => `${this.$i18n.t('springs.status_options.'+data[name])}`,
                },
                {
                    label: this.$i18n.t('springs.feedback'),
                    name: 'feedback',
                    orderable: false,
                    transform: ({data, name}) => `${ (data[name].length > 0) ? (data[name]).length : ''}`,
                },
            ],
            pagination: {
                'size': 'small',
            },
            translate: {
                nextButton: 'Next', previousButton: 'Previous', placeholderSearch: 'SearchnnnnTable'
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
    }
}
</script>
