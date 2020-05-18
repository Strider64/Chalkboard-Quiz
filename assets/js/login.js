'use strict';

document.querySelector('.logout').style.display = "none";

const startBtn = document.querySelector('#startBtn');
const loginBtn = document.querySelector('#loginMessage');
const loginForm = document.querySelector('#loginForm');

const   loginUrl = 'loginUser.php';
var loginData = {},
        submitBtn = document.querySelector('#submit'),
        loginInfo = document.querySelector('#loginInfo'),
        loginWelcome = document.querySelector('.welcome');

startBtn.style.visibility = "hidden";

loginForm.style.display = "none";

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
    //console.log("loginData", loginData);
    if (parsedData !== false ) { 
        
        startBtn.style.visibility = "visible";
        document.querySelector('.logout').style.display = "block";
        loginForm.style.display = "none";
        loginInfo.style.display = 'block';
        loginWelcome.textContent = `Welcome, ${parsedData.username}!`;
    }
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

const displayForm = (e) => {
    e.preventDefault();
    const loginForm = document.querySelector('#loginForm');
    document.querySelector('#registerMessage').style.display = "none";
    loginBtn.style.display = "none";
    loginForm.style.display = "block";
    loginBtn.removeEventListener('click', loginForm, false);
};

loginBtn.addEventListener('click', displayForm, false);

const login = (e) => {
    e.preventDefault();
    loginData.username = document.querySelector('#username').value;
    loginData.password = document.querySelector('#password').value;
    
    
    
    createLoginRequest(loginUrl, loginUISuccess, loginUIError);
};

submitBtn.addEventListener('click', login, false);


