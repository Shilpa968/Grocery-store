// Used for Search Bar
let searchForm = document.querySelector('.search-form');
document.querySelector('#search-btn').onclick = () =>
{
    searchForm.classList.toggle('active');

     shoppingCart.classList.remove('active');   
     loginForm.classList.remove ('active');
     navbar.classList.remove('active');
}
//Used for shopping cart
let shoppingCart = document.querySelector('.shopping-cart');
document.querySelector('#cart-btn').onclick = () =>
{
    shoppingCart.classList.toggle('active');

     searchForm.classList.remove('active');  
     loginForm.classList.remove ('active');
     navbar.classList.remove('active');
}
let cart = [];
let cartContainer = document.querySelector('#shopping-cart');
let totalDisplay = cartContainer.querySelector('.total');

// Add to Cart functionality
document.querySelectorAll('.products .btn').forEach((btn, index) => {
    btn.addEventListener('click', () => {
        let productBox = btn.closest('.box');
        let productName = productBox.querySelector('h1').innerText;
        let productPrice = productBox.querySelector('.price').innerText.replace('₹','').replace('/-','').trim();
        let productImg = productBox.querySelector('img').src;

        // Check if already in cart
        let existing = cart.find(item => item.name === productName);
        if (existing) {
            existing.qty++;
        } else {
            cart.push({ name: productName, price: parseInt(productPrice), qty: 1, img: productImg });
        }

        renderCart();
    });
});

// Function to render cart items
function renderCart() {
    // Remove old boxes
    cartContainer.querySelectorAll('.box').forEach(box => box.remove());

    let total = 0;

    cart.forEach((item, idx) => {
        total += item.price * item.qty;

        let div = document.createElement('div');
        div.classList.add('box');
        div.innerHTML = `
            <i class="fa fa-trash" data-index="${idx}"></i>
            <img src="${item.img}" alt="${item.name}">
            <div class="content">
                <h3>${item.name}</h3>
                <span class="price">₹${item.price}/-</span>
                <span class="quantity">Qty: ${item.qty}</span>
            </div>
        `;
        cartContainer.insertBefore(div, totalDisplay);
    });

    totalDisplay.innerText = "Total: ₹" + total;

    // Add delete functionality
    cartContainer.querySelectorAll('.fa-trash').forEach(icon => {
        icon.addEventListener('click', () => {
            let index = icon.getAttribute('data-index');
            cart.splice(index, 1);
            renderCart();
        });
    });
}
let cartSection = document.querySelector('.shopping-cart');
document.querySelector('#cart-btn').onclick = () => {
    cartSection.classList.toggle('active');
};

//Used for login formm 
let loginForm = document.querySelector('.login-form');
document.querySelector('#login-btn').onclick = () =>
{
    loginForm.classList.toggle('active');

     searchForm.classList.remove('active'); 
     shoppingCart.classList.remove('active');   
     navbar.classList.remove('active');
}
//Used for mobile view to show menu  bar
let navbar = document.querySelector('.navbar');
document.querySelector('#menu-btn').onclick = () =>
{
    navbar.classList.toggle('active');

     searchForm.classList.remove('active'); 
     shoppingCart.classList.remove('active');   
     loginForm.classList.remove ('active');
}

window.onscroll = () =>
{
     searchForm.classList.remove('active'); 
     shoppingCart.classList.remove('active');   
     loginForm.classList.remove ('active');
     navbar.classList.remove('active');
}

// Slider code using swipperjs.com website
var swiper = new Swiper(".product-slider", {
      loop:true,
      spaceBetween: 20,
      autoplay: {
        delay: 3500,
        disableOnInteraction: false,
      },
      breakpoints: {
        0: {
          slidesPerView: 1,

        },
        768: {
          slidesPerView: 2,
   
        },
        1020: {
          slidesPerView: 3,
          
        },
      },
    });
// review Slider
    var swiper = new Swiper(".review-slider", {
      loop:true,
      spaceBetween: 20,
      autoplay: {
        delay: 3500,
        disableOnInteraction: false,
      },
      breakpoints: {
        0: {
          slidesPerView: 1,

        },
        768: {
          slidesPerView: 2,
   
        },
        1020: {
          slidesPerView: 3,
          
        },
      },
    });