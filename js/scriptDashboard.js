document.addEventListener('DOMContentLoaded', () => {
    const favoritos = JSON.parse(localStorage.getItem('favoritos') || '[]');
    document.querySelectorAll('.favorito').forEach(el => {
        const id = el.getAttribute('onclick').match(/\d+/)[0];
        if (favoritos.includes(id)) {
            el.querySelector('i').classList.add('text-yellow-400');
        } else {
            el.querySelector('i').classList.add('text-gray-400');
        }
    });
});

function toggleFavorito(id, element) {
    let favoritos = JSON.parse(localStorage.getItem('favoritos') || '[]');
    const index = favoritos.indexOf(String(id));

    const icon = element.querySelector('i');
    if (index > -1) {
        favoritos.splice(index, 1);
        icon.classList.remove('text-yellow-400');
        icon.classList.add('text-gray-400');
    } else {
        favoritos.push(String(id));
        icon.classList.remove('text-gray-400');
        icon.classList.add('text-yellow-400');
    }
    localStorage.setItem('favoritos', JSON.stringify(favoritos));
}