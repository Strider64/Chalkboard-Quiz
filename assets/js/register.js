'use strict';

const d = document;

const unavailable = d.querySelector('.unavailable');
const recommendation = d.querySelector('.recommendation');
var username = d.querySelector('#username');
var password = d.querySelector('#password');
var data = {};

/* Success function utilizing FETCH */
const duplicateUISuccess = function (status) {
    /*
     * Make <span> HTML tag visible to highlight message
     * in red.
     */
    if (status) {
        unavailable.style.display = "inline-block";
    } else {
        unavailable.style.display = "none";
    }
};

/* If Database Table fails to update data in mysql table */
const duplicateUIError = function (error) {
    console.log("Database Table did not load", error);
};


const passwordUISuccess = function(status) {
    if (status === "Strong") {
        recommendation.style['color'] = 'green';
    } else {
        recommendation.style['color'] = 'red';
    }
    recommendation.textContent = status;
};

const passwordUIError = (error) => {
  console.log("Database Table did not load", error);  
};


/*
 * Grab the status if there is an error.
 */
const handleSaveErrors = function (response) {
    if (!response.ok) {
        throw (response.status + ' : ' + response.statusText);
    }
    return response.json();
};

/*
 *  Fetch ($.get in jQuery) that basic is a simplified newer Ajax
 *  protocol (function?). 
 */
const checkRequest = (sendUrl, succeed, fail) => {
    fetch(sendUrl, {
        method: 'POST', // or 'PUT'
        body: JSON.stringify(data)

    })
            .then((response) => handleSaveErrors(response))
            .then((data) => succeed(data))
            .catch((error) => fail(error));
};

/*
 *  Grab the keystrokes
 */
const checkForDuplicate = (e) => {
    e.preventDefault();
    const sendUrl = 'checkName.php'; // Name of php file:
    data.username = username.value; // Put the keystrokes in object var:    
    
    /* 
     *  Call the checkRequest Function using sendUrl variable as the name
     *  of the php file that will be used to check against the database table.
     */ 
    
    checkRequest(sendUrl, duplicateUISuccess, duplicateUIError); 
};

/*
 * Add an Event Listener to check for username already in 
 * database table. When the person types every keyup stroke
 * is checked against the database table. 
 */

username.addEventListener('keyup', checkForDuplicate, false);

const strongPassword = (e) => {
    e.preventDefault();
    const sendUrl = 'strongPassword.php';
    data.password = password.value;
    checkRequest(sendUrl, passwordUISuccess, passwordUIError);
};

password.addEventListener('keyup', strongPassword, false);