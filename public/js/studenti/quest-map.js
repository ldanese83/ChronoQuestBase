(function () {
    const FLAG_WIDTH = 60;
    const FLAG_HEIGHT = 60;
    const FLAG_OFFSET_X = 15;
    const FLAG_OFFSET_Y = 45;

    function drawFlag(ctx, image, chapter) {
        const drawX = Number(chapter.x || 0) - FLAG_OFFSET_X;
        const drawY = Number(chapter.y || 0) - FLAG_OFFSET_Y;

        ctx.drawImage(image, drawX, drawY, FLAG_WIDTH, FLAG_HEIGHT);
        ctx.fillStyle = 'white';
        ctx.font = 'bold 12px Arial';
        ctx.fillText(String(chapter.progressive || ''), drawX + 25, drawY + 25);

        return { x: drawX, y: drawY, width: FLAG_WIDTH, height: FLAG_HEIGHT, link: String(chapter.link || '#') };
    }

    document.addEventListener('DOMContentLoaded', function () {
        const canvas = document.getElementById('immagine_piantina');
        if (!canvas) {
            return;
        }

        const ctx = canvas.getContext('2d', { alpha: false });
        const mapSrc = canvas.dataset.mapSrc || '';
        const chapters = Array.isArray(window.studentQuestMapData?.chapters) ? window.studentQuestMapData.chapters : [];
        const hitBoxes = [];

        const mapImage = new Image();

        mapImage.onload = function () {
            const scale = Math.min(canvas.width / mapImage.width, canvas.height / mapImage.height);
            const width = mapImage.width * scale;
            const height = mapImage.height * scale;
            const offsetX = (canvas.width - width) / 2;
            const offsetY = (canvas.height - height) / 2;

            ctx.drawImage(mapImage, offsetX, offsetY, width, height);

            let loadedCount = 0;
            if (chapters.length === 0) {
                return;
            }

            chapters.forEach(function (chapter) {
                const flag = new Image();
                flag.src = chapter.flagImage || '/assets/images/bandiera_rossa.png';
                flag.onload = function () {
                    hitBoxes.push(drawFlag(ctx, flag, chapter));
                    loadedCount += 1;

                    if (loadedCount === chapters.length) {
                        canvas.addEventListener('click', function (event) {
                            const rect = canvas.getBoundingClientRect();
                            const mouseX = event.clientX - rect.left;
                            const mouseY = event.clientY - rect.top;

                            hitBoxes.forEach(function (box) {
                                if (box.link === '#') {
                                    return;
                                }

                                if (mouseX >= box.x && mouseX <= box.x + box.width && mouseY >= box.y && mouseY <= box.y + box.height) {
                                    window.location.href = box.link;
                                }
                            });
                        });
                    }
                };
            });
        };

        mapImage.src = mapSrc;
    });
})();
