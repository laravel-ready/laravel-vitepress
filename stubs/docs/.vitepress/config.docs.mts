import type { UserConfig } from "vitepress";

/**
 * User-editable VitePress configuration.
 *
 * Customize this file to change your documentation's appearance and structure.
 * Do NOT edit config.mts - it contains package-managed settings.
 */
const docsConfig: UserConfig = {
    // Site metadata
    title: "My Documentation",
    description: "Documentation powered by VitePress and Laravel",

    // Theme configuration
    themeConfig: {
        logo: "/logo.svg",

        nav: [
            { text: "Home", link: "/" },
            { text: "Guide", link: "/guide/" },
            { text: "API", link: "/api/" },
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
                        {
                            text: "Advanced Usage",
                            link: "/guide/advanced-usage",
                        },
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

        socialLinks: [{ icon: "github", link: "https://github.com/your-repo" }],

        footer: {
            message: "Released under the MIT License.",
            copyright: `Copyright © ${new Date().getFullYear()}`,
        },

        search: {
            provider: "local",
        },

        editLink: {
            pattern:
                "https://github.com/your-repo/edit/main/resources/docs/:path",
            text: "Edit this page on GitHub",
        },
    },
};

export default docsConfig;
