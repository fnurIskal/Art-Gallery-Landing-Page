// Demo stand-in for POST /api/contact on the static Vercel preview.
// Mirrors the PHP validator's rules so the UI behaves identically, but stores
// nothing: the real backend (PHP + MySQL) lives in the Docker setup.
const rules = {
  full_name: (v) => v.length >= 2 && v.length <= 120 ? null : 'Ad soyad 2–120 karakter olmalıdır.',
  email: (v) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v) && v.length <= 254 ? null : 'Geçerli bir e-posta adresi girin.',
  phone: (v) => /^\+?[0-9][0-9 ()-]{8,23}[0-9]$/.test(v) ? null : 'Geçerli bir telefon numarası girin.',
  message: (v) => v.length >= 10 && v.length <= 5000 ? null : 'Mesaj 10–5000 karakter olmalıdır.',
};

export default async function handler(request, response) {
  response.setHeader('Cache-Control', 'no-store');
  if (request.method !== 'POST') {
    return response.status(405).json({ success: false, message: 'Yalnızca POST kabul edilir.' });
  }

  const body = typeof request.body === 'string' ? safeJson(request.body) : (request.body ?? {});
  const errors = {};
  for (const [field, check] of Object.entries(rules)) {
    const value = String(body[field] ?? '').replace(/<[^>]*>/g, '').trim();
    const error = check(value);
    if (error) errors[field] = error;
  }
  if (String(body.contact_check ?? '').trim() !== '') errors.form = 'Form doğrulanamadı.';

  if (Object.keys(errors).length > 0) {
    return response.status(422).json({ success: false, message: 'Lütfen işaretlenen alanları kontrol edin.', errors });
  }

  return response.status(200).json({
    success: true,
    demo: true,
    message: 'Demo yayını: form doğrulandı ancak bu ortamda veritabanı bulunmadığı için mesaj kaydedilmedi. Tam sürüm Docker (PHP + MySQL) ile çalışır.',
  });
}

function safeJson(text) {
  try { return JSON.parse(text); } catch { return {}; }
}
