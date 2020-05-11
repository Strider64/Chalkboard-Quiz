'use strict';


const loginBtn = document.querySelector('#loginMessage');
const   loginUrl = 'loginUser.php';
var loginData = {},
        submitBtn = document.querySelector('#submit');

/*
 * Throw error response if something is wrong: 
 */
const handleLoginErrors = (response) => {
    if (!response.ok) {
        throw (response.status + ' : ' + response.statusText);
    }
    return response.json();
};

/* Success function utilizing FETCH */
const loginUISuccess = (parsedData) => {
    console.log('Login was ', parsedData);
    console.log("loginData", loginData);
};

/* If Database Table fails to load then answer a few hard coded Q&A */
const loginUIError = (error) => {
    console.log("Database Table did not load", error);
}


/* create FETCH request */
const createLoginRequest = (url, succeed, fail) => {
    var sendLoginData = loginData;
    loginData = {}; // Destroy the Login Credentials
    fetch(url, {
        method: 'POST', // or 'PUT'
        body: JSON.stringify(sendLoginData)

    })
            .then((response) => handleLoginErrors(response))
            .then((data) => succeed(data))
            .catch((error) => fail(error));
};

const loginForm = (e) => {
    e.preventDefault();
    const loginForm = document.querySelector('#loginForm');
    loginBtn.style.display = "none";
    loginForm.style.display = "block";
    loginBtn.removeEventListener('click', loginForm, false);
};

loginBtn.addEventListener('click', loginForm, false);

const login = (e) => {
    e.preventDefault();
    loginData.username = document.querySelector('#username').value;
    loginData.password = document.querySelector('#password').value;
    
    
    
    createLoginRequest(loginUrl, loginUISuccess, loginUIError);
};

submitBtn.addEventListener('click', login, false);