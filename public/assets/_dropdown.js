const navbarTogglers = document.querySelectorAll('.dropdown-toggle');
const dropdownMenus = document.querySelectorAll('.dropdown-menu');

// Itera sobre todos os elementos .dropdown-toggle
navbarTogglers.forEach((navbarToggler, i) => {
    // Adiciona um evento de clique para cada elemento .dropdown-toggle
    navbarToggler.addEventListener('click', () => {
        // Fecha os outros dropdowns
        fecharOutrosDropdowns(i);

        // Obtém as coordenadas do botão clicado
        const buttonRect = navbarToggler.getBoundingClientRect();

        // Define a posição do dropdown em relação ao botão
        dropdownMenus[i].style.top = `${buttonRect.bottom}px`;
        dropdownMenus[i].style.left = `${converterValoresParaPorcentagem(buttonRect.left, window.innerWidth)}%`;

        // Alterna a visibilidade do dropdown clicado
        dropdownMenus[i].classList.toggle('show');
    });

    // Adiciona um evento de mouseleave para cada dropdown-menu
    dropdownMenus[i].addEventListener('mouseleave', () => {
        // Fecha o dropdown ao mover o mouse para fora
        dropdownMenus[i].classList.remove('show');
    });
});

// Função para fechar os outros dropdowns
function fecharOutrosDropdowns(index) {
    dropdownMenus.forEach((dropdownMenu, j) => {
        if (j !== index) {
            dropdownMenu.classList.remove('show');
        }
    });
}

// Função para converter valores em porcentagem
function converterValoresParaPorcentagem(valor, max) {
    // Calcula o multiplicador com base no tamanho da tela
    const multiplicador = max < 992 ? 4.5 : 3;
    // Calcula o valor em porcentagem e aplica o multiplicador
    return ((valor / max) * 100) - ((valor / max) * 100) * multiplicador;
}
