//dropdown menu logic
document.addEventListener('click', function (e) {

    const btn = e.target.closest('[data-action="dropdown-toggle"]'); //gets the button that was clicked. e.target could be the icon or the button itself. we should grab hold the button

    // If clicking the button
    if (btn) {
        const card = btn.closest('.one-card'); //get the parent html of the button, a.k.a the card; returns an html
        const dropdown = card.querySelector('[data-dropdown]'); //from the card itself only, find the dropdown; returns an html

        // close others first
        document.querySelectorAll('[data-dropdown]').forEach(d => {
            if (d !== dropdown) d.classList.add('hidden');
            //in each dropdown, we close that doesnt match the current dropdown to be opened. dropd
        });

        dropdown.classList.toggle('hidden');
        return;
    }

    // If clicking outside
    document.querySelectorAll('[data-dropdown]').forEach(d => {
        d.classList.add('hidden');
    });

});
