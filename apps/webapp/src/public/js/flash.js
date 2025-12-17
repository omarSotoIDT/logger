
document.addEventListener('DOMContentLoaded', () => {
  const cont = document.getElementById('toast-contenedor') || document;

  const closeToast = (toast) => {
    if (!toast || toast.dataset.closing === '1') return;

    toast.dataset.closing = '1';
    toast.classList.add('ocultar');

    const removeToast = () => {
      if (toast && toast.parentNode) toast.remove();
    };

    toast.addEventListener(
      'animationend',
      (ev) => {
        if (ev.animationName === 'toast-out') removeToast();
      },
      { once: true }
    );

    setTimeout(removeToast, 350);
  };

  cont.addEventListener('click', (e) => {
    const closeBtn = e.target.closest('.toast-cerrar');
    if (!closeBtn) return;

    e.preventDefault();
    e.stopPropagation();

    const toast = closeBtn.closest('.toast');
    closeToast(toast);
  });

  document.querySelectorAll('.toast').forEach((toast) => {
    const ms = Number(toast.dataset.timeout || 0);
    const bar = toast.querySelector('.toast-progress > span');

    if (ms <= 0) {
      toast.querySelector('.toast-progress')?.remove();
      return;
    }

    if (bar) {
      toast._progressAnim = bar.animate(
        [{ transform: 'scaleX(1)' }, { transform: 'scaleX(0)' }],
        { duration: ms, easing: 'linear', fill: 'forwards' }
      );
    }

    toast._autoTimer = setTimeout(() => {
      closeToast(toast);
    }, ms);
  });

  cont.addEventListener('click', (e) => {
    const closeBtn = e.target.closest('.toast-cerrar');
    if (!closeBtn) return;

    const toast = closeBtn.closest('.toast');
    if (!toast) return;

    if (toast._autoTimer) clearTimeout(toast._autoTimer);
    if (toast._progressAnim) toast._progressAnim.cancel();
  }, true); 
});
