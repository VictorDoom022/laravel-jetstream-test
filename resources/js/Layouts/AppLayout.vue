<script setup>
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { Head, Link } from '@inertiajs/inertia-vue3';
import JetApplicationMark from '@/Jetstream/ApplicationMark.vue';
import JetBanner from '@/Jetstream/Banner.vue';
import JetDropdown from '@/Jetstream/Dropdown.vue';
import JetDropdownLink from '@/Jetstream/DropdownLink.vue';
import JetNavLink from '@/Jetstream/NavLink.vue';
import JetResponsiveNavLink from '@/Jetstream/ResponsiveNavLink.vue';

defineProps({
    title: String,
});

const showingNavigationDropdown = ref(false);

const switchToTeam = (team) => {
    Inertia.put(route('current-team.update'), {
        team_id: team.id,
    }, {
        preserveState: false,
    });
};

const logout = () => {
    Inertia.post(route('logout'));
};
</script>

<template>
    <div>
        <Head :title="title" />

        <JetBanner />

        <div class="min-h-screen bg-gray-100">
            <nav class="navbar bg-base-100">
                <div class="navbar-start">
                    <div class="dropdown">
                        <label tabindex="0" class="btn btn-ghost lg:hidden">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16" /></svg>
                        </label>
                        <ul tabindex="0" class="menu menu-compact dropdown-content mt-3 p-2 shadow bg-base-100 rounded-box w-52">
                            <li>
                                <Link :href="route('dashboard')" :active="route().current('dashboard')">
                                    Dashboard
                                </Link>
                            </li>
                            <li tabindex="0">
                                <a class="justify-between">
                                    Product
                                    <svg class="fill-current" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M8.59,16.58L13.17,12L8.59,7.41L10,6L16,12L10,18L8.59,16.58Z"/></svg>
                                </a>
                                <ul class="p-2">
                                    <li>
                                        <a>Submenu 1</a>
                                    </li>
                                    <li><a>Submenu 2</a></li>
                                </ul>
                            </li>
                            <li><a>Item 3</a></li>
                        </ul>
                    </div>
                    <a class="btn btn-ghost normal-case text-xl">
                        <JetApplicationMark class="block h-9 w-auto" />
                    </a>
                </div>
                <div class="navbar-center hidden lg:flex">
                    <ul class="menu menu-horizontal p-0">
                        <li>
                            <Link :href="route('dashboard')" :active="route().current('dashboard')">
                                Dashboard
                            </Link>
                        </li>
                        <li>
                            <Link :href="route('users')" :active="route().current('users')">
                                Users
                            </Link>
                        </li>
                        <li tabindex="0">
                            <a>
                                Product
                                <svg class="fill-current" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path d="M7.41,8.58L12,13.17L16.59,8.58L18,10L12,16L6,10L7.41,8.58Z"/></svg>
                            </a>
                            <ul class="p-2">
                                <li>
                                    <a>Manage Product Category</a>
                                </li>
                                <li><a>Submenu 2</a></li>
                            </ul>
                        </li>
                        <!-- Teams Dropdown -->
                        <li tabindex="0" v-if="$page.props.jetstream.hasTeamFeatures">
                            <a>
                                Manage Team
                                <svg class="fill-current" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path d="M7.41,8.58L12,13.17L16.59,8.58L18,10L12,16L6,10L7.41,8.58Z"/></svg>
                            </a>
                            <ul class="p-2">
                                <li>
                                    <span class="inline-flex rounded-md">
                                        <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:bg-gray-50 hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition">
                                            {{ $page.props.user.current_team.name }}

                                            <svg
                                                class="ml-2 -mr-0.5 h-4 w-4"
                                                xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20"
                                                fill="currentColor"
                                            >
                                                <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </span>
                                </li>
                                <li>
                                    <Link :href="route('teams.show', $page.props.user.current_team)">
                                        Team Settings
                                    </Link>
                                </li>
                                <li v-if="$page.props.jetstream.canCreateTeams">
                                    <Link  :href="route('teams.create')">
                                        Create New Team
                                    </Link>
                                </li>
                                <li>
                                    <template v-for="team in $page.props.user.all_teams" :key="team.id">
                                        Switch Tean
                                        <form @submit.prevent="switchToTeam(team)">
                                            <Link as="button">
                                                <div class="flex items-center">
                                                    <svg
                                                        v-if="team.id == $page.props.user.current_team_id"
                                                        class="mr-2 h-5 w-5 text-green-400"
                                                        fill="none"
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        stroke="currentColor"
                                                        viewBox="0 0 24 24"
                                                    ><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                    <div>{{ team.name }}</div>
                                                </div>
                                            </Link>
                                        </form>
                                    </template>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="navbar-end">
                    <ul class="menu menu-horizontal p-0">
                        <li tabindex="0">
                            <a>
                                {{ $page.props.user.name }}
                                <svg class="fill-current" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path d="M7.41,8.58L12,13.17L16.59,8.58L18,10L12,16L6,10L7.41,8.58Z"/></svg>
                            </a>
                            <ul class="p-2">
                                <li>
                                    <Link :href="route('profile.show')" :active="route().current('profile.show')">
                                        Profile
                                    </Link>
                                </li>
                                <li v-if="$page.props.jetstream.hasApiFeatures">
                                    <JetResponsiveNavLink :href="route('api-tokens.index')" :active="route().current('api-tokens.index')">
                                        API Tokens
                                    </JetResponsiveNavLink>
                                </li>
                                <li>
                                    <form method="POST" @submit.prevent="logout">
                                        <Link as="button">
                                            Log Out
                                        </Link>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Page Heading -->
            <header v-if="$slots.header" class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>
