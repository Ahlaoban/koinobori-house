/* WordPress supplies wp.media; this module adds no frontend dependency. */
(() => {
  'use strict';
  document.querySelectorAll('#khpm-video .khpm-field').forEach(field => {
    const input = field.querySelector('input');
    const status = field.querySelector('.khpm-name');
    let frame;
    field.querySelector('.khpm-select').addEventListener('click', () => {
      if (!frame) {
        frame = wp.media({
          title: field.dataset.kind === 'video' ? 'Vidéo du koi / Koi video' : 'Image d’aperçu / Poster',
          button: { text: 'Utiliser / Use' }, multiple: false,
          library: { type: field.dataset.kind === 'video' ? ['video/mp4', 'video/webm'] : ['image/jpeg', 'image/png', 'image/webp', 'image/avif'] }
        });
        frame.on('select', () => {
          const media = frame.state().get('selection').first().toJSON();
          input.value = String(media.id);
          status.textContent = media.title || media.filename;
        });
      }
      frame.open();
    });
    field.querySelector('.khpm-remove').addEventListener('click', () => {
      input.value = '0';
      status.textContent = 'Aucun média / No media';
    });
  });
})();
