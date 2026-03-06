import fs from 'node:fs/promises';
import path from 'node:path';
import { fileURLToPath } from 'node:url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const rootDir = path.resolve(__dirname, '..');
const sourceDir = path.join(rootDir, 'node_modules', 'vditor', 'dist');
const targetDir = path.join(rootDir, 'public', 'vendor', 'vditor', 'dist');

async function ensureDir(dir) {
    await fs.mkdir(dir, { recursive: true });
}

async function copyDir(src, dest) {
    await ensureDir(dest);
    const entries = await fs.readdir(src, { withFileTypes: true });
    await Promise.all(
        entries.map(async (entry) => {
            const srcPath = path.join(src, entry.name);
            const destPath = path.join(dest, entry.name);

            if (entry.isDirectory()) {
                await copyDir(srcPath, destPath);
                return;
            }

            if (entry.isFile()) {
                await fs.copyFile(srcPath, destPath);
            }
        }),
    );
}

try {
    await copyDir(sourceDir, targetDir);
    console.log('[postinstall] Copied Vditor assets to public/vendor/vditor/dist');
} catch (error) {
    console.warn('[postinstall] Skipped copying Vditor assets:', error?.message || error);
}
