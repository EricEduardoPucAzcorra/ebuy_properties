// Funcionalidad del menú móvil - Global para todas las páginas
document.addEventListener('DOMContentLoaded', function() {
    console.log('Inicializando menú móvil global...');
    
    const mobileMenuToggle = document.getElementById('mobileMenuToggle');
    const mobileMenuClose = document.getElementById('mobileMenuClose');
    const mobileMenu = document.getElementById('mobileMenu');
    const body = document.body;

    console.log('Elementos del menú:', {
        toggle: mobileMenuToggle,
        close: mobileMenuClose,
        menu: mobileMenu
    });

    // Abrir menú móvil
    if (mobileMenuToggle) {
        mobileMenuToggle.addEventListener('click', function() {
            console.log('Click en botón hamburguesa');
            if (mobileMenu) {
                mobileMenu.classList.add('active');
                body.style.overflow = 'hidden';
                console.log('Menú abierto');
            }
        });
    }

    // Cerrar menú móvil
    if (mobileMenuClose) {
        mobileMenuClose.addEventListener('click', function() {
            console.log('Click en botón cerrar');
            if (mobileMenu) {
                mobileMenu.classList.remove('active');
                body.style.overflow = '';
                console.log('Menú cerrado');
            }
        });
    }

    // Toggle submenús principales (primer nivel) - SOLUCIÓN DEFINITIVA
    // Inicializar todos los submenús principales como ocultos
    document.querySelectorAll('.ebuy-mobile-submenu').forEach(submenu => {
        submenu.style.display = 'none';
        submenu.style.visibility = 'hidden';
    });
    
    // Configurar toggles principales
    document.querySelectorAll('.ebuy-mobile-menu-toggle').forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const targetId = this.getAttribute('data-target');
            const submenu = document.getElementById(targetId);
            const icon = this.querySelector('i:last-child');
            
            console.log('Click principal en:', targetId);
            console.log('Display actual:', submenu ? submenu.style.display : 'no encontrado');
            
            if (submenu) {
                if (submenu.style.display === 'none') {
                    // Mostrar
                    submenu.style.display = 'block';
                    submenu.style.visibility = 'visible';
                    icon.classList.remove('fa-chevron-down');
                    icon.classList.add('fa-chevron-up');
                    console.log('MOSTRAR submenu principal');
                } else {
                    // Ocultar
                    submenu.style.display = 'none';
                    submenu.style.visibility = 'hidden';
                    icon.classList.remove('fa-chevron-up');
                    icon.classList.add('fa-chevron-down');
                    console.log('OCULTAR submenu principal');
                }
            }
        });
    });

    // Toggle submenús anidados (segundo nivel) - SOLUCIÓN DEFINITIVA
    // Inicializar todos los submenús anidados como ocultos
    document.querySelectorAll('.ebuy-mobile-submenu-nested').forEach(submenu => {
        submenu.style.display = 'none';
        submenu.style.visibility = 'hidden';
    });
    
    // Configurar toggles anidados
    document.querySelectorAll('.ebuy-mobile-submenu-toggle').forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const targetId = this.getAttribute('data-target');
            const submenu = document.getElementById(targetId);
            const icon = this.querySelector('i:last-child');
            
            console.log('Click en:', targetId);
            console.log('Display actual:', submenu ? submenu.style.display : 'no encontrado');
            
            if (submenu) {
                if (submenu.style.display === 'none') {
                    // Mostrar
                    submenu.style.display = 'block';
                    submenu.style.visibility = 'visible';
                    icon.classList.remove('fa-chevron-down');
                    icon.classList.add('fa-chevron-up');
                    console.log('MOSTRAR submenu');
                } else {
                    // Ocultar
                    submenu.style.display = 'none';
                    submenu.style.visibility = 'hidden';
                    icon.classList.remove('fa-chevron-up');
                    icon.classList.add('fa-chevron-down');
                    console.log('OCULTAR submenu');
                }
            }
        });
    });

    // Toggle idioma móvil - SOLUCIÓN DEFINITIVA
    const mobileLangCurrent = document.querySelector('.ebuy-mobile-lang-current');
    const mobileLangOptions = document.querySelector('.ebuy-mobile-lang-options');
    
    if (mobileLangCurrent && mobileLangOptions) {
        // Inicializar como oculto
        mobileLangOptions.style.display = 'none';
        mobileLangOptions.style.visibility = 'hidden';
        
        mobileLangCurrent.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            console.log('Click en selector de idioma');
            console.log('Display actual:', mobileLangOptions.style.display);
            
            if (mobileLangOptions.style.display === 'none') {
                // Mostrar opciones
                mobileLangOptions.style.display = 'block';
                mobileLangOptions.style.visibility = 'visible';
                console.log('MOSTRAR opciones de idioma');
            } else {
                // Ocultar opciones
                mobileLangOptions.style.display = 'none';
                mobileLangOptions.style.visibility = 'hidden';
                console.log('OCULTAR opciones de idioma');
            }
        });
    }

    // Cerrar menú al hacer clic fuera
    document.addEventListener('click', function(e) {
        if (mobileMenu && mobileMenuToggle) {
            if (!mobileMenu.contains(e.target) && !mobileMenuToggle.contains(e.target)) {
                mobileMenu.classList.remove('active');
                body.style.overflow = '';
            }
        }
    });

    // Cerrar menú al cambiar de tamaño de ventana a desktop
    window.addEventListener('resize', function() {
        if (mobileMenu && window.innerWidth > 991) {
            mobileMenu.classList.remove('active');
            body.style.overflow = '';
        }
    });

    console.log('Menú móvil global inicializado completamente');
});
