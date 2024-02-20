// https://nuxt.com/docs/api/configuration/nuxt-config
import vuetify, { transformAssetUrls } from 'vite-plugin-vuetify'
import { visualizer } from 'rollup-plugin-visualizer';

export default defineNuxtConfig({
  runtimeConfig: {
    public: {
      API_URL: process.env.API_URL,
    },
  },
  devtools: { enabled: false },
  //...
  build: {
    transpile: ['vuetify'],
  },
  modules: [
    (_options, nuxt) => {
      nuxt.hooks.hook('vite:extendConfig', (config) => {
        // @ts-expect-error
        config.plugins.push(vuetify({ autoImport: true }))
      }),
      'cookie-universal-nuxt'
    },
    //...
  ],
  vite: {
    plugins: [
      visualizer({ open: true, gzipSize: true, brotliSize: true }),
    ],
    vue: {
      template: {
        transformAssetUrls,
      },
    },
  },
})
