document.querySelectorAll('img').forEach((image) => {
    const showFallback = () => image.parentElement?.classList.add('image-unavailable');

    image.addEventListener('error', showFallback, { once: true });

    if (image.complete && image.naturalWidth === 0) {
        showFallback();
    }
});

document.querySelectorAll('.menu-filters').forEach((filters) => {
    const buttons = filters.querySelectorAll('[data-filter]');
    const cards = document.querySelectorAll('.menu-card');

    buttons.forEach((button) => {
        button.addEventListener('click', () => {
            buttons.forEach((item) => item.classList.toggle('is-active', item === button));
            cards.forEach((card) => {
                card.hidden = button.dataset.filter !== 'Semua' && card.dataset.category !== button.dataset.filter;
            });
        });
    });
});
