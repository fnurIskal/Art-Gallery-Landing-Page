// Exports the rendered landing page as a static site for Vercel.
// Usage: node scripts/export-static.mjs [sourceUrl]
// The PHP app must be running (default http://localhost:8080). Output: dist-vercel/
import { cp, mkdir, rm, writeFile } from 'node:fs/promises';
import path from 'node:path';
import { fileURLToPath } from 'node:url';

const root = path.resolve(path.dirname(fileURLToPath(import.meta.url)), '..');
const source = process.argv[2] ?? 'http://localhost:8080/';
const out = path.join(root, 'dist-vercel');

const response = await fetch(source);
if (!response.ok) throw new Error(`Kaynak sayfa alınamadı: ${response.status}`);
let html = await response.text();

// The static build has no PHP session; make it explicit in the markup.
html = html.replace('<body ', '<body data-static-demo ');

await rm(out, { recursive: true, force: true });
await mkdir(path.join(out, 'api'), { recursive: true });
await writeFile(path.join(out, 'index.html'), html, 'utf8');
await cp(path.join(root, 'public/assets'), path.join(out, 'assets'), { recursive: true });
await cp(path.join(root, 'deploy/vercel/api/contact.js'), path.join(out, 'api/contact.js'));
await cp(path.join(root, 'deploy/vercel/vercel.json'), path.join(out, 'vercel.json'));
await writeFile(path.join(out, 'package.json'), JSON.stringify({ name: 'mersin-modern-static', private: true, type: 'module' }, null, 2));

console.log(`Statik çıktı hazır: ${out}`);
