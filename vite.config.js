import { defineConfig, loadEnv } from 'vite';
import laravel from 'laravel-vite-plugin';

export default ({ mode }) => {
    // โหลดค่าจาก .env ตาม mode ปัจจุบัน (development, production)
    const env = loadEnv(mode, process.cwd());

    return defineConfig({
        plugins: [
            laravel({
                input: ['resources/css/app.css', 'resources/js/app.js'],
                refresh: true,
            }),
        ],
        // เพิ่มส่วนนี้เข้าไปเพื่อส่งค่าจาก .env ไปให้ JavaScript
        define: {
            'process.env': {
                VITE_PUSHER_APP_KEY: `"${env.VITE_PUSHER_APP_KEY}"`,
                VITE_PUSHER_APP_CLUSTER: `"${env.VITE_PUSHER_APP_CLUSTER}"`
            }
        }
    });
}