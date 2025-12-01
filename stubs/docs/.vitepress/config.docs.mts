import type { UserConfig } from "vitepress";

/**
 * User-editable VitePress configuration.
 *
 * Customize this file to change your documentation's appearance and structure.
 * Do NOT edit config.mts - it contains package-managed settings.
 */
const userConfig: UserConfig = {
    // Site metadata
    title: "My Documentation",
    description: "Documentation powered by VitePress and Laravel",

    // Theme configuration
    themeConfig: {
        logo: "/logo.svg",

        // Disable default version switcher in favor of custom nav component
        // @ts-expect-error - versionSwitcher is provided by vitepress-versioning-plugin
        versionSwitcher: false,

        nav: [
            { text: "Home", link: "/" },
            { text: "Guide", link: "/guide/" },
            { text: "API", link: "/api/" },
            // Version switcher component (registered in theme/index.ts)
            // @ts-expect-error - component nav items are supported by VitePress
            { component: "VersionSwitcher" },
        ],

        sidebar: {
            "/guide/": [
                {
                    text: "Introduction",
                    items: [
                        { text: "Getting Started", link: "/guide/" },
                        { text: "Installation", link: "/guide/installation" },
                        { text: "Configuration", link: "/guide/configuration" },
                    ],
                },
                {
                    text: "Usage",
                    items: [
                        { text: "Basic Usage", link: "/guide/basic-usage" },
                        { text: "Advanced Usage", link: "/guide/advanced-usage" },
                    ],
                },
            ],
            "/api/": [
                {
                    text: "API Reference",
                    items: [
                        { text: "Overview", link: "/api/" },
                        { text: "Methods", link: "/api/methods" },
                    ],
                },
            ],
        },

        socialLinks: [
            { icon: "github", link: "https://github.com/your-repo" },
        ],

        footer: {
            message: "Released under the MIT License.",
            copyright: `Copyright © ${new Date().getFullYear()}`,
        },

        search: {
            provider: "local",
        },

        editLink: {
            pattern: "https://github.com/your-repo/edit/main/resources/docs/:path",
            text: "Edit this page on GitHub",
        },
    },
};

export default userConfig;
