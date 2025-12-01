import { defineConfig } from "vitepress";

export default defineConfig({
    title: "My Documentation",
    description: "Documentation powered by VitePress and Laravel",
    base: "/docs/",

    head: [["link", { rel: "icon", href: "/docs/favicon.ico" }]],

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

        socialLinks: [
            {
                icon: "github",
                link: "https://github.com/laravel-ready/laravel-vitepress",
            },
        ],

        footer: {
            message: "Released under the MIT License.",
            copyright: "Copyright © " + new Date().getFullYear(),
        },

        search: {
            provider: "local",
        },

        editLink: {
            pattern:
                "https://github.com/laravel-ready/laravel-vitepress/edit/main/resources/docs/:path",
            text: "Edit this page on GitHub",
        },
    },
});
