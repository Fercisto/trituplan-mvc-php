document.addEventListener('DOMContentLoaded', function() {

    if(document.querySelector('.footer__lista')) {
        const enlacesFooter = document.querySelectorAll('.footer__enlace--FAQ');

        enlacesFooter.forEach(enlace => {
            enlace.addEventListener('click', function(event) {
                event.preventDefault();
    
                const id = this.id;
                const preguntaId = `pregunta${id}`;

                const respuesta = document.querySelector(`#${preguntaId} .ayuda__campo--invisible`);
                const pregunta = document.querySelector(`#${preguntaId} .ayuda__pregunta`);
                const boton = document.querySelector(`#${preguntaId} .ayuda__boton`);
    
                if (respuesta && pregunta) {
                    // Agrega o quita la clase 'mostrar' en la respuesta para mostrar u ocultar el contenido
                    respuesta.classList.toggle('mostrar');
                    boton.classList.toggle('rotado');
                    pregunta.classList.toggle('color-cambiado');
                    window.scrollTo(0, pregunta.offsetTop);
                } else {
                    // Redirecciona a la página de ayuda si el panel no está en la página actual
                    window.location.href = `/ayuda#${preguntaId}`;
                }
            });
        });
    
        // Verifica si estamos en la página de ayuda y activa el panel según el fragmento de la URL
        const currentHash = window.location.hash;
        if (currentHash) {
            const targetPanel = document.querySelector(currentHash);
            if (targetPanel) {
                const respuesta = targetPanel.querySelector('.ayuda__campo--invisible');
                const pregunta = targetPanel.querySelector('.ayuda__pregunta');
                const boton = targetPanel.querySelector('.ayuda__boton');
                if (respuesta) {

                    setTimeout(() => {
                        respuesta.classList.add('mostrar');
                        boton.classList.add('rotado');
                        pregunta.classList.add('color-cambiado');
                    }, 1000);
                    
                }
            }
        }
    }
    
    if(document.querySelector('.ayuda')) {
        const Botones = document.querySelectorAll('.ayuda__boton');

        Botones.forEach(boton => {
            boton.addEventListener('click', function() {
                const campo = this.closest('.ayuda__campo');
                const respuesta = campo.querySelector('.ayuda__campo--invisible');
                const pregunta = campo.querySelector('.ayuda__pregunta');
    
                respuesta.classList.toggle('mostrar');
                boton.classList.toggle('rotado');
                pregunta.classList.toggle('color-cambiado');
            });
        });
    }

});