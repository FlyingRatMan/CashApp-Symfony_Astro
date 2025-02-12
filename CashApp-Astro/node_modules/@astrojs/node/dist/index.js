import { AstroError } from 'astro/errors';
export function getAdapter(options) {
    return {
        name: '@astrojs/node',
        serverEntrypoint: '@astrojs/node/server.js',
        previewEntrypoint: '@astrojs/node/preview.js',
        exports: ['handler', 'startServer', 'options'],
        args: options,
        adapterFeatures: {
            buildOutput: 'server',
            edgeMiddleware: false,
        },
        supportedAstroFeatures: {
            hybridOutput: 'stable',
            staticOutput: 'stable',
            serverOutput: 'stable',
            sharpImageService: 'stable',
            i18nDomains: 'experimental',
            envGetSecret: 'stable',
        },
    };
}
export default function createIntegration(userOptions) {
    if (!userOptions?.mode) {
        throw new AstroError(`Setting the 'mode' option is required.`);
    }
    let _options;
    return {
        name: '@astrojs/node',
        hooks: {
            'astro:config:setup': async ({ updateConfig, config }) => {
                updateConfig({
                    image: {
                        endpoint: config.image.endpoint ?? 'astro/assets/endpoint/node',
                    },
                    vite: {
                        ssr: {
                            noExternal: ['@astrojs/node'],
                        },
                    },
                });
            },
            'astro:config:done': ({ setAdapter, config }) => {
                _options = {
                    ...userOptions,
                    client: config.build.client?.toString(),
                    server: config.build.server?.toString(),
                    host: config.server.host,
                    port: config.server.port,
                    assets: config.build.assets,
                };
                setAdapter(getAdapter(_options));
            },
        },
    };
}
