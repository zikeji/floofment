<script setup lang="ts">
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { SharedData, type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { Images, LayoutGrid, Voicemail } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';
import { onBeforeUnmount, onMounted, ref } from 'vue';

const page = usePage<SharedData>();

const phoneRecordingCount = ref(page.props.phoneRecordings?.count ?? 0);
const sharedMemoriesCount = ref(page.props.sharedMemories?.count ?? 0);

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
        icon: LayoutGrid,
    },
    {
        title: 'Phone Recordings',
        href: '/phone-recordings',
        icon: Voicemail,
        badgeNumber: phoneRecordingCount,
    },
    {
        title: 'Shared Memories',
        href: '/shared-memories',
        icon: Images,
        badgeNumber: sharedMemoriesCount,
    },
];

onMounted(function () {
    const channel = Echo.private('Sidebar');
    channel.listen('.PhoneRecordingsCountChanged', (e: { count: number }) => {
        phoneRecordingCount.value = e.count;
    });
    channel.listen('.SharedMemoriesCountChanged', (e: { count: number }) => {
        sharedMemoriesCount.value = e.count;
    });
});

onBeforeUnmount(function () {
    Echo.private('Sidebar').stopListeningToAll();
});
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('dashboard')">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
