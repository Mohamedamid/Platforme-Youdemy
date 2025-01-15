function toggleNav() {
    var navLinks = document.getElementById('navLinks');
    if (navLinks.className.indexOf('active') === -1) {
        navLinks.className += ' active';
    } else {
        navLinks.className = navLinks.className.replace(' active', '');
    }
}