const mobileMenuBtn = document.querySelector('#mobile-menu');
const sideBar = document.querySelector('.sidebar');
const sidebarH2 = document.querySelector('.sidebar h2');

if (mobileMenuBtn){

    mobileMenuBtn.addEventListener('click', function(){
        sideBar.classList.toggle('mostrar');

    });
}

