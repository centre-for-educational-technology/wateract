<template>
    <app-layout>

        <template #header>
            <h1>
                {{ $t('springs.privacy_policy') }}
            </h1>
        </template>

        <privacy-policy-en />

    </app-layout>
</template>

<script>
import AppLayout from './../../Layouts/AppLayout';
import PrivacyPolicyEn from './en/PrivacyPolicy';

export default {
    components: {
        AppLayout,
        PrivacyPolicyEn,
    },
}
</script>
