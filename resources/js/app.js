/* File: resources/js/app.js */

import 'bootstrap';
import flatpickr from "flatpickr";
import Webcam from 'webcamjs';
window.Webcam = Webcam;


flatpickr(".datepicker-dmy", {
    dateFormat: "d/m/Y",
    allowInput: true,     
});

