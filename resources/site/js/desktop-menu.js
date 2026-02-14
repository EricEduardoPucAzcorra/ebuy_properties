// Funcionalidad del menú desktop - Mega menu interactions
document.addEventListener('DOMContentLoaded', function() {
    console.log('Inicializando menú desktop...');
    
    // Handle sidebar item clicks in mega menu
    document.querySelectorAll('.sidebar-item').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const targetId = this.getAttribute('data-target');
            const targetPane = document.getElementById(targetId);
            
            if (!targetPane) {
                console.log('No se encontró el pane con ID:', targetId);
                return;
            }
            
            // Remove active class from all sidebar items in this mega menu
            const megaCard = this.closest('.mega-card');
            if (megaCard) {
                megaCard.querySelectorAll('.sidebar-item').forEach(sidebarItem => {
                    sidebarItem.classList.remove('active');
                });
                
                // Hide all panes in this mega menu
                megaCard.querySelectorAll('.mega-pane').forEach(pane => {
                    pane.classList.remove('active');
                });
            }
            
            // Add active class to clicked item
            this.classList.add('active');
            
            // Show target pane
            targetPane.classList.add('active');
            
            console.log('Activado pane:', targetId);
        });
    });
    
    console.log('Menú desktop inicializado completamente');
});
