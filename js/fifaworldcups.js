const form = document.querySelector('#login');
if (form != null) form.addEventListener('submit', handleSubmit, false); 

function handleSubmit(event) {
    var card = document.querySelector('.card');
    card.classList.remove('loginAnimation');
    card.classList.add('destroyAnimation');
    var form = document.querySelector('#login');
    setTimeout(() => form.submit(), 1000);
    event.preventDefault();
};

const link = document.querySelector('a');
if (link != null) link.addEventListener('click', handleLink, false);

function handleLink(event) {
    var link = document.querySelector('a');
    setTimeout(() => {
        window.location.href = link;
    }, 1500);
};

const avatar = document.querySelector('#avatar');
if (avatar != null) {
    avatar.onmouseover = (event) => {
        var menu = document.querySelector('#menu');
        if (menu != null) menu.classList.add('show');
    };

    avatar.onmouseleave = (event) => {
       var menu = document.querySelector('#menu');
       if (menu != null) setTimeout(() => menu.classList.remove('show'), 1500);
    };
}

const matches = document.querySelectorAll('.match');
if (matches != null && matches.length > 0) matches[0].classList.add('loadAnimation');