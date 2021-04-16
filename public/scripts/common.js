var modal = {
    open: () => {
        let modal = document.getElementById('modal');
        modal.classList.add('is-active');
        document.getElementsByClassName('onFocus')[0].focus();
    },
    close: () => {
        let modal = document.getElementById('modal');
        modal.classList.remove('is-active');
    }
}

document.onkeydown = function (e) {
    // ctrl + enter
    if (e.metaKey && e.key === 'Enter') {
        let modalElement = document.getElementById('modal');
        if (modalElement.classList.contains('is-active')) {
            var html_event = new Event("submit", { "bubbles": true, "cancelable": true });
            document.dispatchEvent(html_event);
            document.form.submit();
        }
        return;
    }
}

document.onkeyup = function (e) {
    // esc
    if (e.key === 'Escape') {
        let modalElement = document.getElementById('modal');
        if (modalElement.classList.contains('is-active')) {
            modal.close();
        }
        return;
    }

    // n
    if (e.key === 'n') {
        let modalElement = document.getElementById('modal');
        if (!modalElement.classList.contains('is-active')) {
            modal.open();
        }
        return;
    }
};