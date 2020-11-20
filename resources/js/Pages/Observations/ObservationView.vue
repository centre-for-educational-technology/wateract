<template>

    <div class="border rounded px-4 py-2 my-2">
        <div v-if="observation.odor">
            <jet-label value="Odor" />
            <div>{{ observation.odor}}</div>
        </div>

        <div v-if="observation.taste">
            <jet-label value="Taste" />
            <div>{{ observation.taste}}</div>
        </div>

        <div v-if="observation.color">
            <jet-label value="Color and turbidity" />
            <div>{{ observation.color}}</div>
        </div>

        <div v-if="observation.description">
            <jet-label value="Description" />
            <div>{{ observation.description}}</div>
        </div>

    </div>


</template>
<script>
import JetLabel from "../../Jetstream/Label";
    export default {
        components: {
            JetLabel,
        },
        props: ['observation'],
    }
</script>
