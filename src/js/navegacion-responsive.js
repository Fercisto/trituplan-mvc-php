document.addEventListener('DOMContentLoaded', function() {


    if(document.querySelector('.header__mobile')) {
        
        const mobileMenu = document.querySelector('.header__mobile');

        mobileMenu.addEventListener('click', navegacionResponsive);

        function navegacionResponsive() {
            const navegacion = document.querySelector('.header__navegacion--derecha');
            navegacion.classList.toggle('mostrar');
        }

    }

});