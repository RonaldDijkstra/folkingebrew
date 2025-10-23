import { defineConfig } from 'vite'
import tailwindcss from '@tailwindcss/vite';
import laravel from 'laravel-vite-plugin'
import { wordpressPlugin, wordpressThemeJson } from '@roots/vite-plugin';
import prefixSelector from 'postcss-prefix-selector';

export default defineConfig({
  base: '/app/themes/folkingebrew/public/build/',
  css: {
    postcss: {
      plugins: [
        prefixSelector({
          prefix: '.editor-styles-wrapper',
          includeFiles: [/editor\.css$/],
          transform: (prefix, selector, prefixedSelector) => {
            // Don't prefix :root, html, body, or selectors that already have the wrapper
            if (selector.match(/^(html|body|:root|::)/)) {
              return selector;
            }
            if (selector.includes('.editor-styles-wrapper')) {
              return selector;
            }
            if (selector.startsWith('@')) {
              return selector;
            }
            if (selector.startsWith(':where') || selector.startsWith(':is')) {
              return selector;
            }
            // Don't prefix WordPress UI elements that are outside the editor canvas
            if (selector.match(/^\.edit-post-|^\.block-editor-block-toolbar/)) {
              return selector;
            }
            // All selectors need a space for WordPress editor styles
            // This targets descendants of .editor-styles-wrapper
            return `${prefix} ${selector}`;
          },
        }),
        // Add !important only to declarations inside @layer utilities
        {
          postcssPlugin: 'add-important-to-layered-utilities',
          Once(root, { result }) {
            if (result.opts.from && result.opts.from.includes('editor.css')) {
              let inUtilitiesLayer = false;

              root.walkAtRules('layer', (atRule) => {
                if (atRule.params === 'utilities') {
                  atRule.walkDecls(decl => {
                    if (!decl.important) {
                      decl.important = true;
                    }
                  });
                }
              });
            }
          }
        }
      ],
    },
  },
  plugins: [
    tailwindcss(),
    laravel({
      input: [
        'resources/css/app.css',
        'resources/js/app.js',
        'resources/css/editor.css',
        'resources/js/editor.js',
      ],
      refresh: true,
    }),

    wordpressPlugin(),

    // Generate the theme.json file in the public/build/assets directory
    // based on the Tailwind config and the theme.json file from base theme folder
    wordpressThemeJson({
      disableTailwindColors: false,
      disableTailwindFonts: false,
      disableTailwindFontSizes: false,
    }),
  ],
  resolve: {
    alias: {
      '@scripts': '/resources/js',
      '@styles': '/resources/css',
      '@fonts': '/resources/fonts',
      '@images': '/resources/images',
    },
  },
})
