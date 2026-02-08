Vue.directive('trans', {
    async bind(el, binding) {
        if (!binding.value) {
            el.innerText = '';
            return;
        }
        // Si ya está en cache, lo pone al instante
        if (window.cacheTraducciones && window.cacheTraducciones[binding.value]) {
            el.innerText = window.cacheTraducciones[binding.value];
        } else {
            el.innerText = '...';
            el.innerText = await window.auto_trans(binding.value);
        }
    },
    async update(el, binding) {
        // Se ejecuta cuando el error cambia
        if (binding.value !== binding.oldValue) {
            if (!binding.value) {
                el.innerText = '';
                return;
            }
            if (window.cacheTraducciones && window.cacheTraducciones[binding.value]) {
                el.innerText = window.cacheTraducciones[binding.value];
            } else {
                el.innerText = '...';
                el.innerText = await window.auto_trans(binding.value);
            }
        }
    }
});
