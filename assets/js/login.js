'use strict';


const loginBtn = document.querySelector('#loginMessage');


const loginForm = (e) => {
    e.preventDefault();
    const loginForm = document.querySelector('#loginForm');
    loginBtn.style.display = "none";
    loginForm.style.display = "block";
};

loginBtn.addEventListener('click', loginForm, false);