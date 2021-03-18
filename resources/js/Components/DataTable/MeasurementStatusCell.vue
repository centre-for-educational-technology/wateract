<template>

    <div>
        <a v-if="data[name] === 'draft'" class="underline"
           :href="'/springs/'+data['spring']['code']+'/analyses/'+data['id']+'/edit'">
            {{ $t('springs.continue_filling') }}
        </a>
        <span class="text-green-500" v-if="data[name] === 'submitted'">
            {{ $t('springs.'+data[name]) }}
        </span>
    </div>

</template>
<script>
export default {
    props: {
        data: {},
        name: {},
    }
}
</script>

