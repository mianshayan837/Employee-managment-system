    document.addEventListener('DOMContentLoaded', function () {
        const flashMessage = document.getElementById('flash-message');

        if (flashMessage) {
            setTimeout(function () {
                flashMessage.style.transition = 'opacity 0.5s ease';
                flashMessage.style.opacity = '0';

                setTimeout(function () {
                    flashMessage.remove();
                }, 500);
            }, 3000); 
        }


          document.querySelectorAll('.team-row').forEach(function (row) {
                const track = row.querySelector('.team-row-track');
                const cards = Array.from(track.children);
                const prevBtn = row.querySelector('.row-nav-prev');
                const nextBtn = row.querySelector('.row-nav-next');
                let page = 0;

                function getVisibleCount() {
                    const w = window.innerWidth;
                    if (w < 640) return 1;
                    if (w < 992) return 2;
                    return 3;
                }

                function update() {
                    const visible = getVisibleCount();
                    const maxPage = Math.max(0, Math.ceil(cards.length / visible) - 1);
                    page = Math.min(page, maxPage);

                    const cardWidth = cards[0] ? cards[0].getBoundingClientRect().width : 0;
                    const gap = parseFloat(getComputedStyle(track).gap) || 0;
                    const offset = page * visible * (cardWidth + gap);

                    track.style.transform = 'translateX(-' + offset + 'px)';

                    prevBtn.disabled = page === 0;
                    nextBtn.disabled = page === maxPage;
                }

                prevBtn.addEventListener('click', function () {
                    page = Math.max(0, page - 1);
                    update();
                });

                nextBtn.addEventListener('click', function () {
                    const visible = getVisibleCount();
                    const maxPage = Math.max(0, Math.ceil(cards.length / visible) - 1);
                    page = Math.min(page + 1, maxPage);
                    update();
                });

                window.addEventListener('resize', update);
                update();
            });
        
        });