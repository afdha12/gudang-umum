import './bootstrap';
import AirDatepicker from 'air-datepicker';
import 'air-datepicker/air-datepicker.css';
import localeId from 'air-datepicker/locale/id';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

document.querySelectorAll('.datepicker').forEach(function(el) {
    new AirDatepicker(el, {
        dateFormat: 'yyyy-MM-dd',
        locale: localeId
    });
});