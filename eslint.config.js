import css from '@eslint/css'
import eslint from '@eslint/js'
import eslintConfigPrettierRecommended from 'eslint-plugin-prettier/recommended'
import pluginVue from 'eslint-plugin-vue'
import globals from 'globals'
import tseslint from 'typescript-eslint'

export default tseslint.config(
    { ignores: ['**/*.d.ts', '**/coverage', '**/dist', '**/node_modules', '**/vendor', 'node_modules/**', 'vendor/**', 'public/**', './**/*.php'] },
    {
        extends: [eslint.configs.recommended, ...tseslint.configs.recommended, ...pluginVue.configs['flat/recommended'], css.configs.recommended],
        files: ['**/*.{ts,vue}'],
        languageOptions: {
            ecmaVersion: 'latest',
            sourceType: 'module',
            globals: globals.browser,
            parserOptions: {
                parser: tseslint.parser,
            },
        },
        rules: {
            'no-console': 'error',
            'no-debugger': 'error',
            'vue/multi-word-component-names': 'off',
            '@typescript-eslint/no-unused-vars': 'off',
            'vue/require-default-prop': 'off',
            'prettier/prettier': ['error', { semi: false }],
        },
    },
    eslintConfigPrettierRecommended,
)
