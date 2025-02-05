// @ts-check
import { defineConfig } from 'astro/config';

console.log("Proxy configured for /api to http://localhost:8000");

// https://astro.build/config
export default defineConfig({
    output: "server",
});

// export default defineConfig({
//     vite: {
//         server: {
//             proxy: {
//                 '/api': {
//                     target: 'http://localhost:8000',
//                     changeOrigin: true,
//                     rewrite: (path) => path.replace(/^\/api/, ''),
//                 },
//             },
//         },
//     },
// });
