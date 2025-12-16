// assets/js/app.js
(function(){
    const itemsPerPage = 8;
    const allItems = Array.from(document.querySelectorAll('.livro-item'));
    let currentPage = 1;

    const searchInput = document.getElementById('searchInput');
    const categoriaSelect = document.getElementById('categoriaSelect');

    function filterItems() {
        const query = (searchInput ? searchInput.value.trim().toLowerCase() : '');
        const categoria = (categoriaSelect ? categoriaSelect.value : '');

        const filtered = allItems.filter(el => {
            const titulo = (el.dataset.titulo || '').toLowerCase();
            const autor = (el.dataset.autor || '').toLowerCase();
            const cat = (el.dataset.categoria || '');

            const matchesSearch = query === '' || titulo.includes(query) || autor.includes(query);
            const matchesCat = categoria === '' || cat === categoria;
            return matchesSearch && matchesCat;
        });

        showPage(1, filtered);
        renderPagination(filtered.length);
    }

    function showPage(page, items = null) {
        currentPage = page;
        const filtered = items || allItems;
        const start = (page-1)*itemsPerPage;
        const end = start + itemsPerPage;

        allItems.forEach(it => it.style.display = 'none');
        filtered.slice(start,end).forEach(it => it.style.display = '');

        document.querySelectorAll('#paginacao li').forEach(li => li.classList.remove('active'));
        const activeLi = document.querySelector('#paginacao li[data-page="'+page+'"]');
        if (activeLi) activeLi.classList.add('active');
    }

    function renderPagination(totalItems) {
        const totalPages = Math.max(1, Math.ceil(totalItems / itemsPerPage));
        const pag = document.getElementById('paginacao');
        if (!pag) return;
        pag.innerHTML = '';
        for (let i=1;i<=totalPages;i++){
            const li = document.createElement('li');
            li.className = 'page-item'+(i===currentPage?' active':'');
            li.setAttribute('data-page', i);
            li.innerHTML = '<a class="page-link">'+i+'</a>';
            li.addEventListener('click', ()=> {
                showPage(i, getFiltered());
            });
            pag.appendChild(li);
        }
    }

    function getFiltered() {
        const query = (searchInput ? searchInput.value.trim().toLowerCase() : '');
        const categoria = (categoriaSelect ? categoriaSelect.value : '');
        return allItems.filter(el => {
            const titulo = (el.dataset.titulo || '').toLowerCase();
            const autor = (el.dataset.autor || '').toLowerCase();
            const cat = (el.dataset.categoria || '');
            const matchesSearch = query === '' || titulo.includes(query) || autor.includes(query);
            const matchesCat = categoria === '' || cat === categoria;
            return matchesSearch && matchesCat;
        });
    }

    if (searchInput) searchInput.addEventListener('input', ()=> filterItems());
    if (categoriaSelect) categoriaSelect.addEventListener('change', ()=> {
        // submit form to apply server-side filter:
        const form = document.getElementById('filterForm');
        if (form) form.submit();
    });

    document.getElementById && document.addEventListener('DOMContentLoaded', function(){
        filterItems();
        // view toggle
        const viewCards = document.getElementById('viewCards');
        const viewTable = document.getElementById('viewTable');
        if (viewCards && viewTable) {
            viewCards.addEventListener('click', function(){
                document.getElementById('cardsContainer').style.display = '';
                document.getElementById('tableContainer').style.display = 'none';
                this.classList.add('active'); viewTable.classList.remove('active');
            });
            viewTable.addEventListener('click', function(){
                document.getElementById('cardsContainer').style.display = 'none';
                document.getElementById('tableContainer').style.display = '';
                this.classList.add('active'); viewCards.classList.remove('active');
            });
        }
    });
})();
