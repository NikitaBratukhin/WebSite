function createRemoveFromCart() {
    var buttonArray = Array.from(document.querySelectorAll('.removeFromCart'));

    buttonArray.forEach(button => {
        button.addEventListener('click', function(event) {
            var phoneIndex = event.target.value;
            console.log(phoneIndex);
            post('purchases.php', { remove_purchase: phoneIndex });
        });
    });
}

function createPrivacyButtonName() {
    var privacyButton = document.querySelector('.privacyButtonName');
    if (privacyButton) {
        privacyButton.addEventListener('click', function(event) {
            var parentDiv = document.querySelector('#profileHeader');
            var newDiv = document.createElement('div');
            newDiv.setAttribute('id', 'privacyFormDiv');
        
            var createInput = document.createElement('input');
            createInput.setAttribute('name', 'changeUserName');
        
            var createInputSubmit = document.createElement('input');
            createInputSubmit.setAttribute('type', 'submit');
            createInputSubmit.setAttribute('value', 'Change');
            createInputSubmit.setAttribute('class', 'privacyButtonName');
        
            var createLabel = document.createElement('label');
            var createForm = document.createElement('form');
        
            parentDiv.appendChild(newDiv);
            newDiv.appendChild(createForm);
            createForm.appendChild(createLabel);
            createLabel.appendChild(createInput);
            createLabel.appendChild(createInputSubmit);
        
            createForm.addEventListener('submit', function(event) {
                event.preventDefault();
                var newName = createForm.querySelector('[name="changeUserName"]').value.trim();
                post('changes.php', { new_name: newName });
            });
        });
    }
}

function setupUserInterface(result) {
    if (result === "true") {
        removeLoginForm();
        createNavigationPanel();
        addLogoutEventListener();
        addAddToCartEventListeners();
    }
}

function removeLoginForm() {
    document.querySelector('#loginCheck').remove();
    document.querySelector('#signup').remove();
}

function createNavigationPanel() {
    var nav = document.querySelector('nav');
    var rightDiv = document.createElement('div');
    var leftNav = document.querySelector('#leftNav');
    var span = document.createElement('span');
    var linkToCart = document.createElement('a');
    var linkToProfile = document.createElement('a');
    var profilePicDiv = document.createElement('div');

    var username = document.getElementById('welcomeMessage').getAttribute('data-username');
    span.textContent = "Welcome " + username + ", check your purchases";
    linkToCart.textContent = "Cart";
    linkToProfile.textContent = "Profile";
    var logOut = createButton("Log Out", "logOutButton");
    var addToCartButton = createButton("Add to Cart", "addToCartButton");

    profilePicDiv.style.backgroundImage = "url(profile_images/" + username + ".png)";
    linkToCart.setAttribute("href", "purchases.php?<?php echo $_COOKIE['username']; ?>");
    linkToProfile.setAttribute("href", "profile.php");
    rightDiv.setAttribute("id", "rightDiv");
    profilePicDiv.setAttribute("id", "profilePicDiv");
    profilePicDiv.classList.add("profile-pic"); 

    nav.appendChild(span);
    nav.appendChild(rightDiv);
    rightDiv.appendChild(linkToCart);
    rightDiv.appendChild(logOut);
    leftNav.appendChild(profilePicDiv); 
    leftNav.appendChild(linkToProfile);
}

function addLogoutEventListener() {
    var logOutButton = document.getElementById("logOutButton");
    logOutButton.addEventListener('click', function(event) {
        delete_cookie('username');
    });
}

function addAddToCartEventListeners() {
    var buttonArray = Array.from(document.querySelectorAll('.addToCartButton'));
    buttonArray.forEach(button => {
        button.addEventListener('click', function(event) {
            var phoneIndex = event.target.value;
            post('purchases.php', { new_purchase: phoneIndex });
        });
    });
}

function createButton(text, id) {
    var button = document.createElement('button');
    button.textContent = text;
    button.setAttribute("id", id);
    return button;
}

function delete_cookie(name) {
    document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
    location.reload();
}

function post(path, params, method) {
    method = method || "post";
    var form = document.createElement("form");
    form.setAttribute("method", method);
    form.setAttribute("action", path);

    for (var key in params) {
        if (params.hasOwnProperty(key)) {
            var hiddenField = document.createElement("input");
            hiddenField.setAttribute("type", "hidden");
            hiddenField.setAttribute("name", key);
            hiddenField.setAttribute("value", params[key]);
            form.appendChild(hiddenField);
        }
    }

    document.body.appendChild(form);
    form.submit();
}
