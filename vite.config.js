import { defineConfig } from 'vite'
import path from 'path'

export default defineConfig({
  root: path.resolve(__dirname, 'assets/build'),
  base: '/wp-content/themes/spaceblocks/assets/build/dist/',
  build: {
    manifest: true,
    outDir: path.resolve(__dirname, 'assets/build/dist'),
    emptyOutDir: true,
    rollupOptions: {
      input: {
        main: path.resolve(__dirname, 'assets/build/src/main.js'),
        editor: path.resolve(__dirname, 'assets/build/src/editor.js'),
      }
    }
  }
})
