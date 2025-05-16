document.addEventListener('DOMContentLoaded', () => {
    const resultRows = document.querySelectorAll('.hclm-search-result-row');
    const countSpan = document.getElementById('hclm-search-results-count');

    if (countSpan) {
        const count = resultRows.length;
        countSpan.innerText = `${count} résultat${count !== 1 ? 's' : ''}`;
    }
});