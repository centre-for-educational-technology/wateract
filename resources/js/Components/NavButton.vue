<template>
    <button class="inline mt-1 sm:ml-2 px-2 sm:px-3 py-1 sm:py-2 text-xs font-semibold leading-normal cursor-pointer bg-blue-100 hover:bg-gray-100 border border-blue-200">
        <inertia-link :href="href">
            <slot></slot>
        </inertia-link>
    </button>
</template>
<script>
export default {
    props: ['href'],
}
</script>
