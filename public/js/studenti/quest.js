(function () {
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('searchInput');
        if (!searchInput) {
            return;
        }

        searchInput.addEventListener('keyup', function () {
            const filter = String(searchInput.value || '').toLowerCase();
            const quests = document.getElementsByClassName('quest-item');

            Array.prototype.forEach.call(quests, function (questElement) {
                const questName = String(questElement.getAttribute('data-name') || '').toLowerCase();
                questElement.style.display = questName.includes(filter) ? '' : 'none';
            });
        });
    });
})();
