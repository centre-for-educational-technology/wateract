<template>
    <div class="min-h-screen bg-gradient-to-b from-gray-100 to-white flex flex-col h-full">

        <!-- Navigation -->
        <nav class="relative bg-no-repeat bg-springs-header bg-cover">
            <img src="/images/springs-header.png" style="vertical-align: top; width: 100%; opacity: 0"/>

            <div class="absolute" style="top:0;width:100%;height:100%">
                <!-- Primary Navigation Menu -->
                <div class="fixed bg-white w-full mx-auto sm:px-6 lg:px-8 z-50" style="top:0;left:0;">
                    <div class="flex justify-between h-10">
                        <div class="flex min-h-full px-4">
                            <!-- Logo -->
                            <div class="flex-shrink-0 flex items-center font-semibold text-xl">
                                <a href="/">
                                    {{  $t('springs.wateract') }}
                                </a>
                            </div>

                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                <jet-nav-link href="/springs" :active="$page.currentRouteName === 'springs.index'">
                                    {{ $t('springs.springs_map') }}
                                </jet-nav-link>
                            </div>
                            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                <jet-nav-link href="/about-springs" :active="$page.currentRouteName === 'about-springs'">
                                    {{ $t('springs.about_springs') }}
                                </jet-nav-link>
                            </div>
                            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                <jet-nav-link href="/instructions" :active="$page.currentRouteName === 'instructions'">
                                    {{ $t('springs.instructions') }}
                                </jet-nav-link>
                            </div>
                            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                <jet-nav-link href="/news" :active="$page.currentRouteName === 'news'">
                                    {{ $t('springs.news') }}
                                </jet-nav-link>
                            </div>
                            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                <jet-nav-link href="/about-wateract" :active="$page.currentRouteName === 'about-wateract'">
                                    {{ $t('springs.about_wateract') }}
                                </jet-nav-link>
                            </div>
                            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex" v-if="$page.user">
                                <jet-nav-link href="/dashboard" :active="$page.currentRouteName === 'dashboard'">
                                    {{ $t('springs.dashboard') }}
                                </jet-nav-link>
                            </div>
                        </div>

                        <div class="hidden sm:flex sm:items-center sm:ml-6" v-if="!$page.user">
                            <div class="text-sm text-gray-500 pr-10"><locale-dropdown></locale-dropdown></div>
                            <a href="/register" class="text-sm text-gray-700 underline">{{ $t('springs.register') }}</a>
                            <a href="/login" class="mx-3 text-sm text-gray-700 underline">{{ $t('springs.login') }}</a>
                        </div>

                        <!-- Settings Dropdown -->
                        <div class="hidden sm:flex sm:items-center sm:ml-6" v-if="$page.user">
                            <div class="text-sm text-gray-500 pr-10"><locale-dropdown></locale-dropdown></div>
                            <div class="text-sm text-gray-500">{{ $page.user.name }}</div>
                            <div class="ml-2 relative">
                                <jet-dropdown align="right" width="48">
                                    <template #trigger>
                                        <button class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition duration-150 ease-in-out">
                                            <img class="h-8 w-8 rounded-full object-cover" :src="$page.user.profile_photo_url" :alt="$page.user.name" />
                                        </button>
                                    </template>

                                    <template #content>
                                        <!-- Account Management -->
                                        <div class="block px-4 py-2 text-xs text-gray-400" v-if="($page.user.roles).length > 0">
                                            {{ $t('springs.'+$page.user.roles[0].name) }}
                                        </div>
                                        <div class="block px-4 py-2 text-xs text-gray-400" v-if="($page.user.roles).length === 0">
                                            {{ $t('springs.spring_enthusiast') }}
                                        </div>

                                        <jet-dropdown-link href="/user/profile">
                                            {{ $t('profile.profile')}}
                                        </jet-dropdown-link>

                                        <jet-dropdown-link href="/admin/users" v-if="can('edit user')">
                                            {{ $t('springs.users') }}
                                        </jet-dropdown-link>

                                        <jet-dropdown-link href="/admin" v-if="can('administrate')">
                                            Admin
                                        </jet-dropdown-link>

                                        <jet-dropdown-link href="/user/api-tokens" v-if="$page.jetstream.hasApiFeatures">
                                            API Tokens
                                        </jet-dropdown-link>

                                        <div class="border-t border-gray-100"></div>

                                        <!-- Team Management -->
                                        <template v-if="$page.jetstream.hasTeamFeatures">
                                            <div class="block px-4 py-2 text-xs text-gray-400">
                                                Manage Team
                                            </div>

                                            <!-- Team Settings -->
                                            <jet-dropdown-link :href="'/teams/' + $page.user.current_team.id">
                                                Team Settings
                                            </jet-dropdown-link>

                                            <jet-dropdown-link href="/teams/create" v-if="$page.jetstream.canCreateTeams">
                                                Create New Team
                                            </jet-dropdown-link>

                                            <div class="border-t border-gray-100"></div>

                                            <!-- Team Switcher -->
                                            <div class="block px-4 py-2 text-xs text-gray-400">
                                                Switch Teams
                                            </div>

                                            <template v-for="team in $page.user.all_teams">
                                                <form @submit.prevent="switchToTeam(team)">
                                                    <jet-dropdown-link as="button">
                                                        <div class="flex items-center">
                                                            <svg v-if="team.id == $page.user.current_team_id" class="mr-2 h-5 w-5 text-green-400" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                            <div>{{ team.name }}</div>
                                                        </div>
                                                    </jet-dropdown-link>
                                                </form>
                                            </template>

                                            <div class="border-t border-gray-100"></div>

                                        </template>

                                        <!-- Authentication -->
                                        <form @submit.prevent="logout">
                                            <jet-dropdown-link as="button">
                                                {{ $t('profile.logout')}}
                                            </jet-dropdown-link>
                                        </form>

                                    </template>



                                </jet-dropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-mr-2 flex items-center sm:hidden">
                            <button @click="showingNavigationDropdown = ! showingNavigationDropdown" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path :class="{'hidden': showingNavigationDropdown, 'inline-flex': ! showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                    <path :class="{'hidden': ! showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                </div>

                <header>
                    <div class="max-w-7xl hidden lg:block mx-auto px-4 sm:px-6 lg:px-8 text-xl sm:text-2xl lg:text-3xl font-semibold text-center lg:pt-20">
                        <slot name="header"></slot>
                    </div>
                </header>

            </div>
            <!-- Page Heading -->
            <!--<header class="border space-x-8 sm:-my-px sm:ml-10 sm:flex">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <slot name="header"></slot>
            </div>
            </header>-->

        </nav>
           <!-- </div>-->

            <!-- Responsive Navigation Menu -->
            <div :class="{'block': showingNavigationDropdown, 'hidden': ! showingNavigationDropdown}" class="sm:hidden">
                <div class="pt-2 pb-3 space-y-1">
                    <jet-responsive-nav-link href="/springs" :active="$page.currentRouteName == 'springs'">
                        {{ $t('springs.springs_map') }}
                    </jet-responsive-nav-link>
                    <jet-responsive-nav-link href="/about-springs" :active="$page.currentRouteName == 'about-springs'">
                        {{ $t('springs.about_springs') }}
                    </jet-responsive-nav-link>
                    <jet-responsive-nav-link href="/instructions" :active="$page.currentRouteName == 'instructions'">
                        {{ $t('springs.instructions') }}
                    </jet-responsive-nav-link>
                    <jet-responsive-nav-link href="/news" :active="$page.currentRouteName == 'news'">
                        {{ $t('springs.news') }}
                    </jet-responsive-nav-link>
                    <jet-responsive-nav-link href="/about-wateract" :active="$page.currentRouteName == 'about-wateract'">
                        {{ $t('springs.about_wateract') }}
                    </jet-responsive-nav-link>
                    <div v-if="!$page.user" class="border-t border-b border-gray-200">
                        <div><a href="/login" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out">
                            {{ $t('springs.login') }}</a></div>
                        <div><a href="/register" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out">
                            {{ $t('springs.register') }}</a></div>
                    </div>
                </div>

                <!-- Responsive Settings Options -->
                <div class="pt-4 pb-1 border-t border-gray-200" v-if="$page.user">
                    <div class="flex items-center px-4">
                        <div class="flex-shrink-0">
                            <img class="h-10 w-10 rounded-full" :src="$page.user.profile_photo_url" :alt="$page.user.name" />
                        </div>

                        <div class="ml-3">
                            <div class="font-medium text-base text-gray-800">{{ $page.user.name }}</div>
                            <div class="font-medium text-sm text-gray-500">{{ $page.user.email }}</div>
                        </div>
                    </div>

                    <div class="mt-3 space-y-1">

                        <jet-responsive-nav-link href="/dashboard" :active="$page.currentRouteName === 'dashboard'">
                            {{ $t('springs.dashboard') }}
                        </jet-responsive-nav-link>

                        <jet-responsive-nav-link href="/user/profile" :active="$page.currentRouteName == 'profile.show'">
                            {{ $t('profile.profile')}}
                        </jet-responsive-nav-link>

                        <jet-responsive-nav-link href="/admin/users" v-if="$page.user && can('edit user')" :active="$page.currentRouteName === 'users.index'">
                            {{ $t('springs.users') }}
                        </jet-responsive-nav-link>

                        <jet-responsive-nav-link href="/admin" v-if="$page.user && can('administrate')" :active="$page.currentRouteName === 'admin'">
                            Admin
                        </jet-responsive-nav-link>

                        <jet-responsive-nav-link href="/user/api-tokens" :active="$page.currentRouteName == 'api-tokens.index'" v-if="$page.jetstream.hasApiFeatures">
                            API Tokens
                        </jet-responsive-nav-link>

                        <!-- Authentication -->
                        <form method="POST" @submit.prevent="logout">
                            <jet-responsive-nav-link as="button">
                                {{ $t('profile.logout')}}
                            </jet-responsive-nav-link>
                        </form>

                        <!-- Team Management -->
                        <template v-if="$page.jetstream.hasTeamFeatures">
                            <div class="border-t border-gray-200"></div>

                            <div class="block px-4 py-2 text-xs text-gray-400">
                                Manage Team
                            </div>

                            <!-- Team Settings -->
                            <jet-responsive-nav-link :href="'/teams/' + $page.user.current_team.id" :active="$page.currentRouteName == 'teams.show'">
                                Team Settings
                            </jet-responsive-nav-link>

                            <jet-responsive-nav-link href="/teams/create" :active="$page.currentRouteName == 'teams.create'">
                                Create New Team
                            </jet-responsive-nav-link>

                            <div class="border-t border-gray-200"></div>

                            <!-- Team Switcher -->
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                Switch Teams
                            </div>

                            <template v-for="team in $page.user.all_teams">
                                <form @submit.prevent="switchToTeam(team)" :key="team.id">
                                    <jet-responsive-nav-link as="button">
                                        <div class="flex items-center">
                                            <svg v-if="team.id == $page.user.current_team_id" class="mr-2 h-5 w-5 text-green-400" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            <div>{{ team.name }}</div>
                                        </div>
                                    </jet-responsive-nav-link>
                                </form>
                            </template>
                        </template>
                    </div>

                    <div class="border-t border-gray-200"></div>



                </div>
            </div>
           <!-- </div>
        </nav>-->

        <header class="lg:hidden">
            <div class="max-w-7xl mx-auto font-semibold text-xl pt-6 px-4 sm:px-6 lg:px-8">
                <slot name="header"></slot>
            </div>
        </header>

        <!-- Page Content -->
        <main class="mb-auto flex-1">
            <slot></slot>
        </main>

        <!-- Modal Portal -->
        <portal-target name="modal" multiple>
        </portal-target>

        <footer>
            <div class="w-full relative bg-no-repeat bg-springs-footer bg-cover">
                <img src="/images/springs-footer.png" style="vertical-align: bottom; width: 100%; opacity: 0"/>
            </div>
        </footer>

    </div>
</template>

<script>
    import JetApplicationLogo from './../Jetstream/ApplicationLogo'
    import JetApplicationMark from './../Jetstream/ApplicationMark'
    import JetDropdown from './../Jetstream/Dropdown'
    import JetDropdownLink from './../Jetstream/DropdownLink'
    import JetNavLink from './../Jetstream/NavLink'
    import JetResponsiveNavLink from './../Jetstream/ResponsiveNavLink'
    import LocaleDropdown from './../Components/LocaleDropdown'

    export default {
        components: {
            JetApplicationLogo,
            JetApplicationMark,
            JetDropdown,
            JetDropdownLink,
            JetNavLink,
            JetResponsiveNavLink,
            LocaleDropdown,
        },

        data() {
            return {
                showingNavigationDropdown: false,
            }
        },

        methods: {
            switchToTeam(team) {
                this.$inertia.put('/current-team', {
                    'team_id': team.id
                }, {
                    preserveState: false
                })
            },

            logout() {
                axios.post('/logout').then(response => {
                    window.location = '/';
                })
            },
        },

        computed: {
            path() {
                return window.location.pathname
            }
        }
    }
</script>
