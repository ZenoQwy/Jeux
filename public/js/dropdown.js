function setDropdownText(selectedOption) {
    document.getElementById("dropdownText").innerText = selectedOption;
}

// Récupère le paramètre 'sort' dans l'URL et met à jour le texte du dropdown en conséquence
const urlParams = new URLSearchParams(window.location.search);
const sortParam = urlParams.get('sort');

if (sortParam === 'likeasc') {
  setDropdownText('Nombre de likes ( croissant )');
} else if (sortParam === 'likedesc') {
  setDropdownText('Nombre de likes ( décroissant )');
}

if (sortParam === 'asc') {
  setDropdownText('Prix ( croissant )');
} else if (sortParam === 'desc') {
  setDropdownText('Prix ( décroissant )');
}

