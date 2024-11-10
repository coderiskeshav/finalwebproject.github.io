const showSignUp = document.getElementById('showSignUp');
const showSignIn = document.getElementById('showSignIn');
const signUpForm = document.getElementById('signUp');
const signInForm = document.getElementById('signIn');

// Show Sign-Up form
showSignUp.addEventListener('click', function() {
    signInForm.style.display = "none";
    signUpForm.style.display = "block";
});

// Show Sign-In form
showSignIn.addEventListener('click', function() {
    signUpForm.style.display = "none";
    signInForm.style.display = "block";
});
