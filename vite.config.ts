import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

export default defineConfig({
  plugins: [
    laravel({
      input: ['resources/js/bootstrap.js', 'resources/js/app.jsx', 'resources/css/app.css'],
      refresh: true,
    }),
    react({
      // TSX-Support
      include: ['resources/js/**/*.tsx', 'resources/js/**/*.jsx'],
    }),
  ],
  resolve: {
    extensions: ['.js', '.jsx', '.ts', '.tsx'],
  },
});
