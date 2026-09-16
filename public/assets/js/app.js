const nav = document.querySelector('[data-nav]');
const navToggle = document.querySelector('[data-nav-toggle]');
const navMenu = document.querySelector('[data-nav-menu]');
const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
const heroSelection = document.querySelector('[data-hero-selection]');
const heroPause = document.querySelector('[data-hero-pause]');
heroPause?.addEventListener('click', () => {
  const paused = heroPause.getAttribute('aria-pressed') !== 'true';
  heroPause.setAttribute('aria-pressed', String(paused));
  heroPause.setAttribute('aria-label', paused ? 'Eser akışını sürdür' : 'Eser akışını duraklat');
  heroPause.textContent = paused ? '▷' : 'Ⅱ';
  heroSelection.classList.toggle('is-paused', paused);
});
if (heroSelection) {
  new IntersectionObserver(([entry]) => {
    heroSelection.classList.toggle('is-offscreen', !entry.isIntersecting);
  }).observe(heroSelection);
  document.addEventListener('visibilitychange', () => {
    heroSelection.classList.toggle('is-page-hidden', document.hidden);
  });
}

const updateNavigation = () => nav?.classList.toggle('is-scrolled', window.scrollY > 24);
window.addEventListener('scroll', updateNavigation, { passive: true });
updateNavigation();

const mobileNavigation = window.matchMedia('(max-width: 1023px)');
const syncNavigationAccess = () => {
  if (!navMenu) return;
  const open = navToggle?.getAttribute('aria-expanded') === 'true';
  navMenu.inert = mobileNavigation.matches && !open;
};
mobileNavigation.addEventListener('change', syncNavigationAccess);
syncNavigationAccess();

navToggle?.addEventListener('click', () => {
  const open = navToggle.getAttribute('aria-expanded') !== 'true';
  navToggle.setAttribute('aria-expanded', String(open));
  navMenu?.classList.toggle('is-open', open);
  syncNavigationAccess();
});

navMenu?.querySelectorAll('a').forEach((link) => link.addEventListener('click', () => {
  navToggle?.setAttribute('aria-expanded', 'false');
  navMenu.classList.remove('is-open');
  syncNavigationAccess();
}));

document.addEventListener('keydown', (event) => {
  if (event.key === 'Escape' && navToggle?.getAttribute('aria-expanded') === 'true') {
    navToggle.setAttribute('aria-expanded', 'false');
    navMenu?.classList.remove('is-open');
    syncNavigationAccess();
    navToggle.focus();
  }
});

document.querySelectorAll('.faq-list button').forEach((button) => {
  button.addEventListener('click', () => {
    const willOpen = button.getAttribute('aria-expanded') !== 'true';
    document.querySelectorAll('.faq-list button').forEach((item) => {
      item.setAttribute('aria-expanded', 'false');
      item.closest('article')?.classList.remove('is-open');
      const answer = document.getElementById(item.getAttribute('aria-controls'));
      if (answer) {
        answer.setAttribute('aria-hidden', 'true');
        answer.inert = true;
      }
    });
    button.setAttribute('aria-expanded', String(willOpen));
    const answer = document.getElementById(button.getAttribute('aria-controls'));
    button.closest('article')?.classList.toggle('is-open', willOpen);
    if (answer) {
      answer.setAttribute('aria-hidden', String(!willOpen));
      answer.inert = !willOpen;
    }
  });
});

const archiveStage = document.querySelector('[data-archive-stage]');
const archiveDetail = document.querySelector('[data-archive-detail]');
const archiveClose = document.querySelector('[data-archive-close]');
const archiveGrid = document.querySelector('[data-archive-grid]');
const archiveMedia = document.querySelector('[data-archive-media]');
const archiveCards = [...document.querySelectorAll('[data-archive-card]')];
let activeArchiveCard = null;
let archiveTransitioning = false;
let archiveCloseQueued = false;
let archiveMotion = null;
let archiveCounterFrame = 0;
let archiveRun = 0;
let archiveOpenScroll = 0;
const archiveNumberFields = ['works', 'artists', 'visitors', 'duration'];
const stopArchiveNumbers = () => cancelAnimationFrame(archiveCounterFrame);
const animateArchiveNumbers = (card) => {
  stopArchiveNumbers();
  const fields = archiveNumberFields.map((key, index) => {
    const label = card.dataset[key] ?? '';
    const match = label.match(/^([\d.]+)(.*)$/);
    return { element: archiveDetail.querySelector(`[data-archive-${key}]`),
      value: match ? Number(match[1].replaceAll('.', '')) : 0,
      suffix: match?.[2] ?? '', label, delay: 260 + index * 80 };
  });
  const started = performance.now();
  const tick = (now) => {
    let complete = true;
    fields.forEach(({ element, value, suffix, label, delay }) => {
      const progress = prefersReducedMotion ? 1 : Math.min(1, Math.max(0, (now - started - delay) / 1100));
      element.textContent = progress === 1 ? label : `${Math.round(value * (1 - (1 - progress) ** 3)).toLocaleString('tr-TR')}${suffix}`;
      if (progress < 1) complete = false;
    });
    if (!complete) archiveCounterFrame = requestAnimationFrame(tick);
  };
  tick(started);
};

const setArchiveText = (selector, value) => {
  const target = archiveDetail?.querySelector(selector);
  if (target) target.textContent = value ?? '';
};

const populateArchive = (card) => {
  if (!archiveDetail) return;
  const index = Number(card.dataset.index ?? 0);
  const image = archiveDetail.querySelector('[data-archive-image]');
  if (image instanceof HTMLImageElement) {
    image.src = card.dataset.image ?? '';
    image.alt = card.dataset.title ? `${card.dataset.title} sergisinden bir görünüm` : '';
  }
  setArchiveText('[data-archive-meta]', card.dataset.meta);
  setArchiveText('[data-archive-index]', `ARCHIVE / ${String(index + 1).padStart(2, '0')}`);
  setArchiveText('[data-archive-title]', card.dataset.title);
  setArchiveText('[data-archive-description]', card.dataset.description);
  setArchiveText('[data-archive-works]', card.dataset.works);
  setArchiveText('[data-archive-artists]', card.dataset.artists);
  setArchiveText('[data-archive-visitors]', card.dataset.visitors);
  setArchiveText('[data-archive-duration]', card.dataset.duration);
  archiveNumberFields.forEach((key) => {
    setArchiveText(`[data-archive-${key}]`, (card.dataset[key] ?? '').replace(/^[\d.]+/, '0'));
  });
  setArchiveText('[data-archive-position]', `${String(index + 1).padStart(2, '0')} / ${String(archiveCards.length).padStart(2, '0')}`);
  setArchiveText('[data-archive-caption]', card.dataset.title);
  archiveDetail.setAttribute('aria-label', `${card.dataset.title ?? 'Sergi'} arşiv detayı`);
};

const cardToPostFrames = (card, media) => {
  const source = card.getBoundingClientRect();
  const target = media.getBoundingClientRect();
  // Match the workshop FLIP transition: a short translation and opacity only.
  // Large image scaling forces costly texture resampling on lower-end GPUs.
  const dx = Math.max(-72, Math.min(72, source.left - target.left));
  const dy = Math.max(-32, Math.min(32, source.top - target.top));
  return [
    { transform: `translate3d(${dx}px, ${dy}px, 0)`, opacity: 0 },
    { transform: 'translate3d(0, 0, 0)', opacity: 1 },
  ];
};

const openArchive = async (card) => {
  if (!archiveStage || !archiveDetail || !archiveMedia || archiveTransitioning || !archiveDetail.hidden) return;
  archiveTransitioning = true;
  const run = ++archiveRun;
  activeArchiveCard = card;
  archiveOpenScroll = window.scrollY;
  archiveCloseQueued = false;
  populateArchive(card);
  card.setAttribute('aria-expanded', 'true');
  // Decode before making the large image visible; reuse the already loaded card.
  const detailImage = archiveDetail.querySelector('[data-archive-image]');
  try { await detailImage.decode(); } catch { /* A failed image must not lock the UI. */ }
  if (run !== archiveRun) return;
  card.classList.add('is-active');
  archiveDetail.hidden = false;
  archiveDetail.inert = false;
  archiveStage.classList.add('is-opening');

  const motion = archiveMedia.animate(cardToPostFrames(card, archiveMedia), {
    duration: prefersReducedMotion ? 0 : 280,
    easing: 'cubic-bezier(.77, 0, .175, 1)',
    fill: 'both',
  });
  archiveMotion = motion;
  archiveDetail.classList.add('is-visible');
  animateArchiveNumbers(card);

  try { await motion.finished; } catch { return; }
  if (run !== archiveRun) return;
  archiveStage.classList.remove('is-opening');
  archiveStage.classList.add('is-open');
  archiveDetail.classList.add('is-visible');
  archiveGrid.inert = true;
  motion.cancel();
  archiveTransitioning = false;
  if (archiveCloseQueued) { closeArchive(); return; }
  archiveClose?.focus({ preventScroll: true });
};

const closeArchive = async () => {
  if (archiveStage?.classList.contains('is-closing')) return;
  if (archiveTransitioning && !archiveStage?.classList.contains('is-opening')) { archiveCloseQueued = true; return; }
  if (!archiveStage || !archiveDetail || !archiveMedia || archiveDetail.hidden || !activeArchiveCard) return;
  ++archiveRun;
  stopArchiveNumbers();
  const currentTransform = getComputedStyle(archiveMedia).transform;
  const currentOpacity = getComputedStyle(archiveMedia).opacity;
  archiveMotion?.cancel();
  archiveTransitioning = true;
  const card = activeArchiveCard;
  const shouldRestoreFocus = archiveDetail.contains(document.activeElement);
  archiveDetail.classList.remove('is-visible');
  archiveDetail.classList.add('is-closing');
  archiveStage.classList.remove('is-open', 'is-opening');
  archiveStage.classList.add('is-closing');
  archiveGrid.inert = false;

  const motion = archiveMedia.animate([
    { transform: currentTransform, opacity: currentOpacity },
    cardToPostFrames(card, archiveMedia)[0],
  ], {
    duration: prefersReducedMotion ? 0 : 220,
    easing: 'cubic-bezier(.22, 1, .36, 1)',
    fill: 'forwards',
  });
  archiveMotion = motion;

  try { await motion.finished; } catch { return; }
  motion.cancel();
  archiveDetail.hidden = true;
  archiveDetail.inert = true;
  archiveDetail.classList.remove('is-closing');
  archiveStage.classList.remove('is-closing');
  card.classList.remove('is-active');
  card.setAttribute('aria-expanded', 'false');
  activeArchiveCard = null;
  archiveTransitioning = false;
  archiveCloseQueued = false;
  if (shouldRestoreFocus) card.focus({ preventScroll: true });
};

const switchArchive = async (direction) => {
  if (!archiveDetail || archiveDetail.hidden || archiveTransitioning || !activeArchiveCard) return;
  archiveTransitioning = true;
  stopArchiveNumbers();
  archiveDetail.classList.remove('is-visible');
  const currentIndex = archiveCards.indexOf(activeArchiveCard);
  const nextIndex = (currentIndex + direction + archiveCards.length) % archiveCards.length;
  const nextCard = archiveCards[nextIndex];
  const fadeOut = archiveDetail.animate([
    { opacity: 1 },
    { opacity: 0 },
  ], { duration: prefersReducedMotion ? 0 : 150, easing: 'cubic-bezier(.4, 0, 1, 1)', fill: 'forwards' });
  try { await fadeOut.finished; } catch { return; }
  activeArchiveCard.setAttribute('aria-expanded', 'false');
  activeArchiveCard.classList.remove('is-active');
  activeArchiveCard = nextCard;
  nextCard.setAttribute('aria-expanded', 'true');
  nextCard.classList.add('is-active');
  populateArchive(nextCard);
  try { await archiveDetail.querySelector('[data-archive-image]').decode(); } catch {}
  fadeOut.cancel();
  archiveDetail.classList.add('is-visible');
  animateArchiveNumbers(nextCard);
  const fadeIn = archiveDetail.animate([
    { opacity: 0 },
    { opacity: 1 },
  ], { duration: prefersReducedMotion ? 0 : 240, easing: 'cubic-bezier(.22, 1, .36, 1)' });
  try { await fadeIn.finished; } catch { return; }
  archiveTransitioning = false;
  if (archiveCloseQueued) closeArchive();
};

// Warm the browser cache with the large cover on intent, so the click-to-post
// transition never waits on a network round trip or a cold decode.
const warmedArchiveImages = new Set();
const warmArchiveImage = (card) => {
  const source = card.dataset.image;
  if (!source || warmedArchiveImages.has(source)) return;
  warmedArchiveImages.add(source);
  const image = new Image();
  image.decoding = 'async';
  image.src = source;
};
archiveCards.forEach((card) => {
  card.addEventListener('click', () => openArchive(card));
  card.addEventListener('pointerenter', () => warmArchiveImage(card), { passive: true });
  card.addEventListener('focus', () => warmArchiveImage(card));
});
archiveClose?.addEventListener('click', closeArchive);
// Preserve the row's layout and scroll position throughout the return motion.
const dismissArchiveOnScroll = () => {
  if (activeArchiveCard && !archiveStage?.classList.contains('is-closing')) closeArchive();
};
window.addEventListener('scroll', () => {
  // Ignore tiny layout/focus adjustments; a deliberate scroll still closes it.
  if (Math.abs(window.scrollY - archiveOpenScroll) > 65) dismissArchiveOnScroll();
}, { passive: true });
archiveGrid?.addEventListener('scroll', dismissArchiveOnScroll, { passive: true });
archiveStage?.addEventListener('wheel', dismissArchiveOnScroll, { passive: true });
archiveStage?.addEventListener('touchmove', dismissArchiveOnScroll, { passive: true });
archiveDetail?.querySelector('[data-archive-prev]')?.addEventListener('click', () => switchArchive(-1));
archiveDetail?.querySelector('[data-archive-next]')?.addEventListener('click', () => switchArchive(1));

const programGrid = document.querySelector('[data-program-grid]');
if (programGrid) {
  const programCards = [...programGrid.querySelectorAll('[data-program-card]')];
  programCards.forEach((card) => {
    card.querySelector('.program-summary')?.addEventListener('click', () => {
      if (card.classList.contains('is-active')) return;
      const previousRects = new Map(programCards.map((item) => [item, item.getBoundingClientRect()]));

      programGrid.prepend(card);
      programCards.forEach((item) => {
        const active = item === card;
        item.classList.toggle('is-active', active);
        item.querySelector('.program-summary')?.setAttribute('aria-expanded', String(active));
        const detail = item.querySelector('.program-detail');
        if (detail) detail.inert = !active;
      });

      if (prefersReducedMotion) return;
      programCards.forEach((item) => {
        const previous = previousRects.get(item);
        const current = item.getBoundingClientRect();
        if (!previous) return;
        item.animate([
          { transform: `translate(${previous.left - current.left}px, ${previous.top - current.top}px)`, opacity: .82 },
          { transform: 'translate(0, 0)', opacity: 1 },
        ], {
          duration: 280,
          easing: 'cubic-bezier(.77, 0, .175, 1)',
        });
      });
    });
  });
}

const reviewSection = document.querySelector('[data-reviews]');

if (reviewSection) {
  const reviewObserver = new IntersectionObserver((entries) => {
    reviewSection.classList.toggle('in-view', entries[0]?.isIntersecting ?? false);
  }, { threshold: 0.2 });
  reviewObserver.observe(reviewSection);
}

document.addEventListener('visibilitychange', () => {
  reviewSection?.classList.toggle('is-page-hidden', document.hidden);
});

document.addEventListener('keydown', (event) => {
  if (event.key === 'Escape' && archiveDetail && !archiveDetail.hidden) closeArchive();
});

const counterGroup = document.querySelector('[data-counter-group]');
if (counterGroup) {
  const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const animateCounters = () => {
    counterGroup.querySelectorAll('[data-count]').forEach((element) => {
      const target = Number(element.dataset.count);
      if (reducedMotion) {
        element.textContent = target.toLocaleString('tr-TR');
        return;
      }
      const startedAt = performance.now();
      const tick = (now) => {
        const progress = Math.min(1, (now - startedAt) / 1600);
        const eased = 1 - Math.pow(1 - progress, 3);
        element.textContent = Math.round(target * eased).toLocaleString('tr-TR');
        if (progress < 1) requestAnimationFrame(tick);
      };
      requestAnimationFrame(tick);
    });
  };
  const observer = new IntersectionObserver((entries) => {
    if (entries.some((entry) => entry.isIntersecting)) {
      animateCounters();
      observer.disconnect();
    }
  }, { threshold: 0.35 });
  observer.observe(counterGroup);
}

const countdown = document.querySelector('[data-countdown]');
if (countdown) {
  const deadline = new Date(countdown.dataset.deadline).getTime();
  const renderCountdown = () => {
    const remaining = deadline - Date.now();
    if (!Number.isFinite(deadline) || remaining <= 0) {
      countdown.hidden = true;
      return false;
    }
    const units = {
      days: Math.floor(remaining / 86400000),
      hours: Math.floor((remaining / 3600000) % 24),
      minutes: Math.floor((remaining / 60000) % 60),
      seconds: Math.floor((remaining / 1000) % 60),
    };
    Object.entries(units).forEach(([unit, value]) => {
      const target = countdown.querySelector(`[data-${unit}]`);
      if (target) target.textContent = String(value).padStart(2, '0');
    });
    return true;
  };
  if (renderCountdown()) window.setInterval(renderCountdown, 1000);
}

// Guided chatbot: predefined questions rendered by the server, answers appended
// via textContent so no HTML from data attributes is ever interpreted.
const chatbot = document.querySelector('[data-chatbot]');
if (chatbot) {
  const panel = chatbot.querySelector('[data-chatbot-panel]');
  const toggle = chatbot.querySelector('[data-chatbot-toggle]');
  const log = chatbot.querySelector('[data-chatbot-log]');
  const questions = [...chatbot.querySelectorAll('[data-chatbot-question]')];
  let replyTimer = 0;

  const setOpen = (open) => {
    panel.hidden = !open;
    panel.inert = !open;
    toggle.setAttribute('aria-expanded', String(open));
    toggle.querySelector('strong').textContent = open ? 'Kapat' : 'Soru sor';
    if (open) (questions.find((button) => !button.disabled) ?? panel.querySelector('[data-chatbot-close]'))?.focus({ preventScroll: true });
  };

  const appendBubble = (text, role) => {
    const item = document.createElement('li');
    // Full class names keep Tailwind's content scanner aware of both variants.
    item.className = role === 'user' ? 'chatbot-bubble chatbot-bubble-user' : 'chatbot-bubble chatbot-bubble-bot';
    item.textContent = text;
    log.append(item);
    log.scrollTo({ top: log.scrollHeight, behavior: prefersReducedMotion ? 'auto' : 'smooth' });
    return item;
  };

  toggle.addEventListener('click', () => setOpen(panel.hidden));
  chatbot.querySelectorAll('[data-chatbot-close]').forEach((button) => button.addEventListener('click', () => {
    setOpen(false);
    if (button.tagName === 'BUTTON') toggle.focus({ preventScroll: true });
  }));
  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && !panel.hidden) { setOpen(false); toggle.focus({ preventScroll: true }); }
  });

  questions.forEach((button) => button.addEventListener('click', () => {
    if (button.disabled) return;
    appendBubble(button.textContent.trim(), 'user');
    button.disabled = true;
    const pending = appendBubble('Yazıyor…', 'bot');
    pending.classList.add('is-typing');
    window.clearTimeout(replyTimer);
    replyTimer = window.setTimeout(() => {
      pending.classList.remove('is-typing');
      pending.textContent = button.dataset.answer ?? '';
      log.scrollTo({ top: log.scrollHeight, behavior: prefersReducedMotion ? 'auto' : 'smooth' });
      if (questions.every((item) => item.disabled)) {
        appendBubble('Başka bir sorun varsa iletişim formundan yazabilirsin.', 'bot');
      }
    }, prefersReducedMotion ? 0 : 550);
  }));
}

const toast = document.querySelector('[data-toast]');
let toastTimer;
const showToast = (message, type = 'success') => {
  if (!toast) return;
  window.clearTimeout(toastTimer);
  toast.querySelector('[data-toast-message]').textContent = message;
  toast.querySelector('[data-toast-icon]').textContent = type === 'success' ? '✓' : '!';
  toast.classList.toggle('is-success', type === 'success');
  toast.classList.toggle('is-error', type !== 'success');
  toast.hidden = false;
  // Replay the enter animation when a message replaces a visible toast.
  toast.getAnimations().forEach((animation) => { animation.cancel(); animation.play(); });
  toastTimer = window.setTimeout(() => { toast.hidden = true; }, 7000);
};
toast?.querySelector('[data-toast-close]')?.addEventListener('click', () => {
  toast.hidden = true;
  window.clearTimeout(toastTimer);
});

const contactForm = document.querySelector('#contact-form');
if (contactForm) {
  const tokenMeta = document.querySelector('meta[name="csrf-token"]');
  const requestToken = contactForm.elements.namedItem('request_token');
  const makeRequestToken = () => globalThis.crypto?.randomUUID?.() ?? 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, (character) => {
    const value = Math.floor(Math.random() * 16);
    return (character === 'x' ? value : (value & 3) | 8).toString(16);
  });
  // Keep the server-generated ID stable through validation failures and retries.
  if (!requestToken.value) requestToken.value = makeRequestToken();

  const clearErrors = () => {
    contactForm.querySelectorAll('[data-error-for]').forEach((element) => { element.textContent = ''; });
    contactForm.querySelectorAll('[aria-invalid="true"]').forEach((element) => element.removeAttribute('aria-invalid'));
  };

  contactForm.addEventListener('submit', async (event) => {
    event.preventDefault();
    clearErrors();

    if (!contactForm.checkValidity()) {
      contactForm.reportValidity();
      return;
    }

    const submitButton = contactForm.querySelector('button[type="submit"]');
    const submitLabel = contactForm.querySelector('[data-submit-label]');
    submitButton.disabled = true;
    submitLabel.textContent = 'Gönderiliyor…';

    const payload = Object.fromEntries(new FormData(contactForm).entries());

    try {
      const response = await fetch(contactForm.action, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-Token': tokenMeta?.content ?? '',
        },
        credentials: 'same-origin',
        body: JSON.stringify(payload),
      });
      const result = await response.json();

      if (!response.ok) {
        Object.entries(result.errors ?? {}).forEach(([field, message]) => {
          const output = contactForm.querySelector(`[data-error-for="${CSS.escape(field)}"]`);
          const input = contactForm.elements.namedItem(field);
          if (output) output.textContent = String(message);
          if (input instanceof HTMLElement) input.setAttribute('aria-invalid', 'true');
        });
        showToast(result.message ?? 'Form gönderilemedi.', 'error');
        return;
      }

      showToast(result.message);
      contactForm.reset();
      contactForm.elements.namedItem('_token').value = tokenMeta?.content ?? '';
      requestToken.value = makeRequestToken();
    } catch {
      showToast('Bağlantı kurulamadı. Bilgileriniz formda korunuyor; lütfen tekrar deneyin.', 'error');
    } finally {
      submitButton.disabled = false;
      submitLabel.textContent = 'Mesajı gönder';
    }
  });
}
