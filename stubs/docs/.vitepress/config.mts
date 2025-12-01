/// <reference types="node" />
import { defineConfig } from "vitepress";
import { config } from "dotenv";
import { resolve } from "path";

// Load environment variables from Laravel's .env file (two levels up from resources/docs)
config({ path: resolve(__dirname, "../../.env") });

// Also try loading from the root .env if resources/docs is at project root
config({ path: resolve(__dirname, "../../../.env") });

// Base URL can be set via VITEPRESS_BASE or VITEPRESS_ROUTE_PREFIX env variable
// Must include leading and trailing slashes
const routePrefix = process.env.VITEPRESS_ROUTE_PREFIX ?? "docs";
const base = process.env.VITEPRESS_BASE ?? `/${routePrefix}/`;

// Log the base URL during build for debugging
console.log(`[VitePress] Building with base URL: ${base}`);

export default defineConfig({
    title: "My Documentation",
    description: "Documentation powered by VitePress and Laravel",
    base: base,

    head: [["link", { rel: "icon", href: `${base}favicon.ico` }]],

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
