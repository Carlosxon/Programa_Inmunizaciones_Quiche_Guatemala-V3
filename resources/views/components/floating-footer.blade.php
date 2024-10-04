<div id="floating-footer" style="display: none; position: fixed; bottom: 10px; right: 10px; background-color: #343a40; color: white; padding: 10px; border-radius: 5px; z-index: 1050;">
    <strong>Programa de Inmunizaciones Quiché v3.0</strong>
    <a href="https://wa.me/TUNUMERODEWHATSAPP" target="_blank" rel="noopener noreferrer" style="color: #17a2b8; margin-left: 10px;">
        <i class="fab fa-whatsapp"></i> Soporte
    </a>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var footer = document.getElementById('floating-footer');
    var toggleButton = document.createElement('button');
    toggleButton.innerHTML = 'i';
    toggleButton.style.cssText = 'position: fixed; bottom: 10px; right: 10px; background-color: #343a40; color: white; border: none; border-radius: 50%; width: 30px; height: 30px; font-size: 16px; cursor: pointer; z-index: 1051;';
    
    document.body.appendChild(toggleButton);

    toggleButton.addEventListener('click', function() {
        if (footer.style.display === 'none') {
            footer.style.display = 'block';
            toggleButton.style.display = 'none';
        } else {
            footer.style.display = 'none';
        }
    });

    footer.addEventListener('click', function(e) {
        if (e.target === footer) {
            footer.style.display = 'none';
            toggleButton.style.display = 'block';
        }
    });
});
</script>