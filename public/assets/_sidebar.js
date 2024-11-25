(() => {
    let navbarHeightMobile = 180;
    let navbarHeightDesktop = 280;
    const sidebarContent = document.querySelector('.sidebar-content');
    // Variável para controlar se o botão de alternância foi clicado
    let toggleClicked = true;
    // Seleciona o elemento da barra lateral
    const venusSidebar = document.querySelector('.venus-sidebar');
    
    //mobile hide sidebar automatically

    if(window.innerWidth < 767) {
        venusSidebar.classList.remove('venus-sidebar-shrink');
        venusSidebar.classList.add('venus-sidebar-expand');
        toggleClicked = false;
    }

    // Adiciona um evento de redimensionamento à janela
    const shiftSideBarContent = () => {
       if(venusSidebar.classList.contains('venus-sidebar-shrink')){
            if(window.innerWidth < 767) {
                sidebarContent.style.transform = `translateX(${navbarHeightMobile + 5}px)`;
                sidebarContent.style.paddingLeft = `0%`;
            } else if(window.innerWidth < 1200){
                sidebarContent.style.paddingLeft = `${navbarHeightMobile + 5}px`;
            }else{
                sidebarContent.style.paddingLeft = `${navbarHeightDesktop + 8}px`;
            }
       }else{
          if(window.innerWidth < 767) {
            sidebarContent.style.transform = `translateX(0%)`;
            sidebarContent.style.paddingLeft = `16%`;
          }else if(window.innerWidth < 1200){
            sidebarContent.style.paddingLeft = `7%`;
        }else{
            sidebarContent.style.paddingLeft = `5.5%`;
          }
       }
    }

    shiftSideBarContent();

    // Seleciona a lista não ordenada dentro da barra lateral
    const navbarHover = document.querySelector('.venus-sidebar .list-unstyled');

    // Seleciona o botão de alternância da barra lateral
    const venusSidebarToggle = document.querySelector('.venus-sidebar-toggle');

    // Adiciona um evento de clique ao botão de alternância da barra lateral
    venusSidebarToggle.addEventListener('click', () => {
        // Alterna o estado do botão de alternância
        toggleClicked = !toggleClicked;

        // Verifica se a barra lateral está expandida
        if (venusSidebar.classList.contains('venus-sidebar-expand')) {
            // Remove a classe de expansão e adiciona a classe de contração
            venusSidebar.classList.remove('venus-sidebar-expand');
            venusSidebar.classList.add('venus-sidebar-shrink');
        } else {
            // Remove a classe de contração e adiciona a classe de expansão
            venusSidebar.classList.remove('venus-sidebar-shrink');
            venusSidebar.classList.add('venus-sidebar-expand');
        }
        shiftSideBarContent();
    });

    // Adiciona um evento de mouseenter à lista não ordenada da barra lateral
    navbarHover.addEventListener('mouseenter', () => {
        // Verifica se a barra lateral está expandida
        if (venusSidebar.classList.contains('venus-sidebar-expand')) {
            // Remove a classe de expansão e adiciona a classe de contração
            venusSidebar.classList.remove('venus-sidebar-expand');
            venusSidebar.classList.add('venus-sidebar-shrink');
        }
        shiftSideBarContent();
    });

    // Adiciona um evento de mouseleave à lista não ordenada da barra lateral
    navbarHover.addEventListener('mouseleave', () => {
        // Verifica se o botão de alternância foi clicado
        if (!toggleClicked) {
            // Remove a classe de contração e adiciona a classe de expansão
            venusSidebar.classList.remove('venus-sidebar-shrink');
            venusSidebar.classList.add('venus-sidebar-expand');
        }
        shiftSideBarContent();
    });
})();
