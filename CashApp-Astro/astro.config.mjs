// @ts-check
import { defineConfig } from 'astro/config';

console.log("Proxy configured for /api to http://localhost:8000");

// https://astro.build/config
export default defineConfig({
    output: "server",
});


