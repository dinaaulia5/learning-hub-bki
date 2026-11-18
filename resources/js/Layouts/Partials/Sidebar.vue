<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import NavLink from "@/Components/NavLink.vue";

import { Card, CardContent } from "@/Components/ui/card";
import { Avatar, AvatarFallback, AvatarImage } from "@/Components/ui/avatar";

import { computed, ref } from "vue";

import {
    IconLayoutDashboard,
    IconShield,
    IconChevronDown,
    IconKey,
    IconUsers,
    IconBriefcase,
    IconCategory,
    IconBook,
    IconBooks,
} from "@tabler/icons-vue";

import { can } from "@/lib/permissions";

const props = defineProps({
    auth: {
        type: Object,
        required: true,
    },

    url: {
        type: String,
        default: "",
    },
});

/*
|--------------------------------------------------------------------------
| Access Management
|--------------------------------------------------------------------------
*/

const hasAccessManagement = computed(() => {
    return can("roles.view") || can("permissions.view");
});

const accessManagementOpen = ref(
    props.url.startsWith("/roles") || props.url.startsWith("/permissions"),
);

/*
|--------------------------------------------------------------------------
| User Management
|--------------------------------------------------------------------------
*/

const hasUserManagement = computed(() => {
    return can("users.view") || can("employees.view");
});

/*
|--------------------------------------------------------------------------
| Master
|--------------------------------------------------------------------------
*/

const hasMaster = computed(() => {
    return (
        can("training-category.view") ||
        can("training-type.view") ||
        can("training.view")
    );
});
</script>

<template>
    <nav class="flex flex-1 flex-col gap-y-6 overflow-y-auto scrollbar-none">
        <!-- ===================================================== -->
        <!-- LOGO -->
        <!-- ===================================================== -->

        <ApplicationLogo url="#" />

        <!-- ===================================================== -->
        <!-- USER PROFILE -->
        <!-- ===================================================== -->

        <Card>
            <CardContent class="flex items-center gap-3 p-3">
                <Avatar>
                    <AvatarImage :src="auth?.avatar" />

                    <AvatarFallback>
                        {{ auth?.name?.substring(0, 1) }}
                    </AvatarFallback>
                </Avatar>

                <div class="flex min-w-0 flex-col">
                    <span
                        class="line-clamp-1 text-sm font-medium tracking-tight"
                    >
                        {{ auth?.name }}
                    </span>

                    <span class="line-clamp-1 text-xs text-muted-foreground">
                        {{ auth?.id }}
                    </span>
                </div>
            </CardContent>
        </Card>

        <!-- ===================================================== -->
        <!-- MENU -->
        <!-- ===================================================== -->

        <ul role="list" class="flex flex-1 flex-col gap-y-2">
            <!-- ================================================= -->
            <!-- GENERAL -->
            <!-- ================================================= -->

            <div class="px-3 py-2 text-sm font-medium text-muted-foreground">
                General
            </div>

            <!-- Dashboard -->
            <NavLink
                title="Dashboard"
                :url="route('dashboard')"
                :active="url.startsWith('/dashboard')"
                :icon="IconLayoutDashboard"
            />

            <!-- ================================================= -->
            <!-- USER MANAGEMENT -->
            <!-- ================================================= -->

            <template v-if="hasUserManagement">
                <div
                    class="px-3 py-2 text-sm font-medium text-muted-foreground"
                >
                    User Management
                </div>

                <!-- Users -->
                <NavLink
                    v-if="can('users.view')"
                    title="Users"
                    :url="route('users.index')"
                    :active="url.startsWith('/users')"
                    :icon="IconUsers"
                />

                <!-- Employees -->
                <NavLink
                    v-if="can('employees.view')"
                    title="Employees"
                    :url="route('employees.index')"
                    :active="url.startsWith('/employees')"
                    :icon="IconBriefcase"
                />
            </template>

            <!-- ================================================= -->
            <!-- ACCESS MANAGEMENT -->
            <!-- ================================================= -->

            <li v-if="hasAccessManagement">
                <button
                    type="button"
                    class="flex w-full items-center gap-x-3 rounded-md px-3 py-2 text-left text-foreground transition-colors hover:bg-muted"
                    @click="accessManagementOpen = !accessManagementOpen"
                >
                    <IconShield class="size-5 shrink-0" />

                    <div class="flex-1">Access Management</div>

                    <IconChevronDown
                        class="size-4 shrink-0 transition-transform duration-200"
                        :class="{
                            'rotate-180': accessManagementOpen,
                        }"
                    />
                </button>

                <!-- Submenu -->
                <div v-show="accessManagementOpen" class="mt-1 ml-8 space-y-1">
                    <!-- Roles -->
                    <NavLink
                        v-if="can('roles.view')"
                        title="Roles"
                        :url="route('roles.index')"
                        :active="url.startsWith('/roles')"
                        :icon="IconShield"
                    />

                    <!-- Permissions -->
                    <NavLink
                        v-if="can('permissions.view')"
                        title="Permissions"
                        :url="route('permissions.index')"
                        :active="url.startsWith('/permissions')"
                        :icon="IconKey"
                    />
                </div>
            </li>

            <!-- ================================================= -->
            <!-- MASTER -->
            <!-- ================================================= -->

            <template v-if="hasMaster">
                <div
                    class="px-3 py-2 text-sm font-medium text-muted-foreground"
                >
                    Master
                </div>

                <!-- Training Category -->
                <NavLink
                    v-if="can('training-category.view')"
                    title="Kategori Pelatihan"
                    :url="route('training.categories.index')"
                    :active="url.startsWith('/training/categories')"
                    :icon="IconCategory"
                />

                <!-- Training Type -->
                <NavLink
                    v-if="can('training-type.view')"
                    title="Tipe Pelatihan"
                    :url="route('training.types.index')"
                    :active="url.startsWith('/training/types')"
                    :icon="IconBooks"
                />

                <!-- Training -->
                <NavLink
                    v-if="can('training.view')"
                    title="Pelatihan"
                    :url="route('trainings.index')"
                    :active="url.startsWith('/trainings')"
                    :icon="IconBook"
                />
            </template>
        </ul>
    </nav>
</template>
