import { NavFooter } from '@/components/nav-footer';
import { NavMain } from '@/components/nav-main';
import { NavUser } from '@/components/nav-user';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/react';
import { 
    BookOpen, 
    LayoutGrid, 
    Home, 
    Users, 
    CreditCard, 
    MessageSquare,
    Settings,
    Building,
    UserCheck
} from 'lucide-react';
import AppLogo from './app-logo';

const footerNavItems: NavItem[] = [
    {
        title: 'Settings',
        href: '/settings',
        icon: Settings,
    },
    {
        title: 'Documentation',
        href: '#',
        icon: BookOpen,
    },
];

export function AppSidebar() {
    const page = usePage<{
        auth?: {
            user?: {
                id: number;
                name: string;
                email: string;
                role: string;
                resident?: {
                    house_id: number;
                };
            };
        };
    }>();
    const user = page.props.auth?.user;
    const userRole = user?.role;

    // Dynamic navigation based on user role
    const getMainNavItems = (): NavItem[] => {
        const baseItems: NavItem[] = [
            {
                title: 'Dashboard',
                href: '/dashboard',
                icon: LayoutGrid,
            },
        ];

        if (userRole === 'administrator') {
            return [
                ...baseItems,
                {
                    title: 'Manajemen Rumah',
                    href: '/houses',
                    icon: Home,
                },
                {
                    title: 'Data Penghuni',
                    href: '/residents',
                    icon: Users,
                },
                {
                    title: 'Pembayaran',
                    href: '/payments',
                    icon: CreditCard,
                },
                {
                    title: 'Keluhan & Perbaikan',
                    href: '/complaints',
                    icon: MessageSquare,
                },
                {
                    title: 'Manajemen User',
                    href: '/users',
                    icon: UserCheck,
                },
            ];
        }

        if (userRole === 'housing_manager') {
            return [
                ...baseItems,
                {
                    title: 'Data Rumah',
                    href: '/houses',
                    icon: Home,
                },
                {
                    title: 'Data Penghuni',
                    href: '/residents',
                    icon: Users,
                },
                {
                    title: 'Pembayaran',
                    href: '/payments',
                    icon: CreditCard,
                },
                {
                    title: 'Keluhan & Perbaikan',
                    href: '/complaints',
                    icon: MessageSquare,
                },
            ];
        }

        if (userRole === 'sales_staff') {
            return [
                ...baseItems,
                {
                    title: 'Rumah Tersedia',
                    href: '/houses?status=available',
                    icon: Home,
                },
                {
                    title: 'Rumah Terjual',
                    href: '/houses?status=sold',
                    icon: Building,
                },
            ];
        }

        if (userRole === 'resident') {
            return [
                ...baseItems,
                {
                    title: 'Data Rumah Saya',
                    href: '/houses/' + (user?.resident?.house_id || ''),
                    icon: Home,
                },
                {
                    title: 'Riwayat Pembayaran',
                    href: '/payments',
                    icon: CreditCard,
                },
                {
                    title: 'Keluhan Saya',
                    href: '/complaints',
                    icon: MessageSquare,
                },
            ];
        }

        return baseItems;
    };

    const mainNavItems = getMainNavItems();

    return (
        <Sidebar collapsible="icon" variant="inset">
            <SidebarHeader>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton size="lg" asChild>
                            <Link href="/dashboard" prefetch>
                                <AppLogo />
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarHeader>

            <SidebarContent>
                <NavMain items={mainNavItems} />
            </SidebarContent>

            <SidebarFooter>
                <NavFooter items={footerNavItems} className="mt-auto" />
                <NavUser />
            </SidebarFooter>
        </Sidebar>
    );
}