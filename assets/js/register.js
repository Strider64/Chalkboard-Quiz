'use strict';

const d = document;
const sendUrl = 'register.php';

var username = d.querySelector('#username');
var checkUsername = {};

/* Success function utilizing FETCH */
const checkUISuccess = function (status) {
    console.log(status);
};

/* If Database Table fails to update data in mysql table */
const checkUIError = function (error) {
    console.log("Database Table did not load", error);
};

const handleSaveErrors = function (response) {
    if (!response.ok) {
        throw (response.status + ' : ' + response.statusText);
    }
    return response.json();
};

const checkRequest = (sendUrl, succeed, fail) => {
    //const data = {username: 'example'};
    fetch(sendUrl, {
        method: 'POST', // or 'PUT'
        body: JSON.stringify(checkUsername)

    })
            .then((response) => handleSaveErrors(response))
            .then((data) => succeed(data))
            .catch((error) => fail(error));
};

checkForDuplicate = (e) => {
    e.preventDefault();
    checkUsername.username = username.value;
    console.log(checkUsername);
    
    saveRequest(sendUrl, checkUISuccess, checkUIError);
}

/*
 * Add an Event Listener to check for username already in 
 * database table. 
 */

username.addEventListener('change', checkForDuplicate, false);


