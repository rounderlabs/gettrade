import Toastify from 'toastify-js';

function getBgColor(type) {
    if (type === 'success') {
        return '#26C714';
    } else if (type === 'primary') {
        return '#1e41aa';
    } else if (type === 'info') {
        return '#395EC1';
    } else if (type === 'danger') {
        return '#d32d2b';
    } else if (type === 'warning') {
        return '#fcc50e';
    } else {
        return '#fff';
    }

}

const toast = (text = '', type = 'success', duration = 3000) => {
    Toastify({
        text: text,
        duration: duration,
        newWindow: true,
        close: true,
        gravity: 'top',
        positionRight: true,
        backgroundColor: getBgColor(type),
        stopOnFocus: true,
    }).showToast();
}

export {toast};
