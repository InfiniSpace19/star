let menu = document.querySelector('#h-menu');
let hamburger = document.querySelector('.mobile');

hamburger.addEventListener('click', function(){
    menu.classList.toggle('displayMenu');
});

// Carousel Section
/*
const galleryContainer = document.querySelector('.gallery-container');
const galleryControlContainer = document.querySelector('.gallery-controls');
const galleryControls = ['prev', 'next'];
const galleryItems = document.querySelectorAll('.gallery-item');

class Carousel {

    constructor(container, items, controls) {
        this.carouselContainer = container;
        this.carouselControls = controls;
        this.carouselArray = [...items];
    }

    updateGallery() {
        this.carouselArray.forEach( element => {
            element.classList.remove('card-1');
            element.classList.remove('card-2');
            element.classList.remove('card-3');
            element.classList.remove('card-4');
            element.classList.remove('card-5');
        });

        this.carouselArray.slice(0, 5).forEach((element, i) => {
            element.classList.add(`card-${i+1}`);
        });
    }

    setCurrentState(direction) {
        if (direction.className == 'gallery-control-prev') {
            this.carouselArray.unshift(this.carouselArray.pop());
        } else {
            this.carouselArray.push(this.carouselArray.shift());
        }
        this.updateGallery();
    }

    setControls() {
        this.carouselControls.forEach(control => {
            galleryControlContainer.appendChild(document.createElement('button')).className = `gallery-control-${control}`;
            document.querySelector(`.gallery-control-${control}`).innerText = control;
        });
    }

    useControls() {
        const triggers = [...galleryControlContainer.childNodes];
        triggers.forEach(control => {
            control.addEventListener('click', e => {
                e.preventDefault();
                this.setCurrentState(control);
            });
        });
    }
}

const cardCarousel = new Carousel(galleryContainer, galleryItems, galleryControls);

cardCarousel.setControls();
cardCarousel.useControls();
*/