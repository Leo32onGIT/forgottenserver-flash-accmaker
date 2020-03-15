/* ----------------------------

	CustomValidation prototype

	- Keeps track of the list of invalidity messages for this input
	- Keeps track of what validity checks need to be performed for this input
	- Performs the validity checks and sends feedback to the front end

---------------------------- */

function CustomValidation(input) {
    this.invalidities = [];
    this.validityChecks = [];

    //add reference to the input node
    this.inputNode = input;

    //trigger method to attach the listener
    this.registerListener();
}

CustomValidation.prototype = {
    addInvalidity: function(message) {
        this.invalidities.push(message);
    },
    getInvalidities: function() {
        return this.invalidities.join('. \n');
    },
    checkValidity: function(input) {
        for (var i = 0; i < this.validityChecks.length; i++) {

            var isInvalid = this.validityChecks[i].isInvalid(input);
            if (isInvalid) {
                this.addInvalidity(this.validityChecks[i].invalidityMessage);
            }

            var requirementElement = this.validityChecks[i].element;

            if (requirementElement) {
                if (isInvalid) {
                    requirementElement.classList.add('invalid');
                    requirementElement.classList.remove('valid');
                } else {
                    requirementElement.classList.remove('invalid');
                    requirementElement.classList.add('valid');
                }

            } // end if requirementElement
        } // end for
    },
    checkInput: function() { // checkInput now encapsulated

        this.inputNode.CustomValidation.invalidities = [];
        this.checkValidity(this.inputNode);

        if (this.inputNode.CustomValidation.invalidities.length === 0 && this.inputNode.value !== '') {
            this.inputNode.setCustomValidity('');
        } else {
            var message = this.inputNode.CustomValidation.getInvalidities();
            this.inputNode.setCustomValidity(message);
        }
    },
    registerListener: function() { //register the listener here

        var CustomValidation = this;

        this.inputNode.addEventListener('keyup', function() {
            CustomValidation.checkInput();
        });


    }

};



/* ----------------------------

	Validity Checks

	The arrays of validity checks for each input
	Comprised of three things
		1. isInvalid() - the function to determine if the input fulfills a particular requirement
		2. invalidityMessage - the error message to display if the field is invalid
		3. element - The element that states the requirement

---------------------------- */

var usernameValidityChecks = [{
        isInvalid: function(input) {
            return input.value.length < 4;
        },
        invalidityMessage: 'Username needs to be at least 4 characters',
        element: document.querySelector('div[id="usernamevalidate"] .input-requirements li:nth-child(1)')
    },
    {
        isInvalid: function(input) {
            return input.value.length > 30;
        },
        invalidityMessage: 'But no more than 30 characters',
        element: document.querySelector('div[id="usernamevalidate"] .input-requirements li:nth-child(2)')
    },
    {
        isInvalid: function(input) {
            var illegalCharacters = input.value.match(/[^a-zA-Z0-9]/g);
            return illegalCharacters ? true : false;
        },
        invalidityMessage: 'Only letters and numbers are allowed',
        element: document.querySelector('div[id="usernamevalidate"] .input-requirements li:nth-child(3)')
    }
];

var passwordValidityChecks = [{
        isInvalid: function(input) {
            return input.value.length < 6;
        },
        invalidityMessage: 'Password needs to be at least 6 characters',
        element: document.querySelector('div[id="passwordvalidate"] .input-requirements li:nth-child(1)')
    },
    {
        isInvalid: function(input) {
            return input.value.length > 50;
        },
        invalidityMessage: 'But no more than 50 characters',
        element: document.querySelector('div[id="passwordvalidate"] .input-requirements li:nth-child(2)')
    }
];

var passwordRepeatValidityChecks = [{
    isInvalid: function() {
        return passwordRepeatInput.value != passwordInput.value;
    },
    invalidityMessage: 'This password needs to match the first one'
}];

var emailValidityChecks = [{
    isInvalid: function(input) {
        return !input.value.match(/^[A-Z0-9._-]+@[A-Z0-9.-]+\.[A-Z0-9.-]+$/i);
    },
    invalidityMessage: 'This is not a valid e-mail address'
}];


/* ----------------------------

	Setup CustomValidation

	Setup the CustomValidation prototype for each input
	Also sets which array of validity checks to use for that input

---------------------------- */

var usernameInput = document.getElementById('accountName');
var passwordInput = document.getElementById('password1');
var passwordRepeatInput = document.getElementById('password2');
var emailInput = document.getElementById('email');

usernameInput.CustomValidation = new CustomValidation(usernameInput);
usernameInput.CustomValidation.validityChecks = usernameValidityChecks;

passwordInput.CustomValidation = new CustomValidation(passwordInput);
passwordInput.CustomValidation.validityChecks = passwordValidityChecks;

passwordRepeatInput.CustomValidation = new CustomValidation(passwordRepeatInput);
passwordRepeatInput.CustomValidation.validityChecks = passwordRepeatValidityChecks;

emailInput.CustomValidation = new CustomValidation(emailInput);
emailInput.CustomValidation.validityChecks = emailValidityChecks;




/* ----------------------------

	Event Listeners

---------------------------- */

var inputs = document.querySelectorAll('input:not([type="submit"])');


var submit = document.querySelector('input[type="submit"');
var form = document.getElementById('registration');

function validate() {
    for (var i = 0; i < inputs.length; i++) {
        inputs[i].CustomValidation.checkInput();
    }
}

submit.addEventListener('click', validate);
form.addEventListener('submit', validate);