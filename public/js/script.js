/*
 * Personalisation JavaScript / JQuery
 */

 // désactive l'appui sur la touche entrée
 $('#map').bind('keypress', function(e)
 {
    if(e.keyCode == 13) {
       return false;
    }
 });

// Configuration datetimepicker pour utiliser FontAwesome 5
$.fn.datetimepicker.Constructor.Default = $.extend($.fn.datetimepicker.Constructor.Default, {
  icons: {
    time: 'far fa-clock',
    date: 'far fa-calendar',
    up: 'fas fa-arrow-up',
    down: 'fas fa-arrow-down',
    previous: 'fas fa-chevron-left',
    next: 'fas fa-chevron-right',
    today: 'far fa-calendar-check-o',
    clear: 'far fa-trash',
    close: 'far fa-times'
  }
});

// Personalisation datetimepicker
$('.datetimepicker').datetimepicker({
    format: 'L LT',
    locale: 'fr',
    defaultDate: moment().hour(0).minute(00),
    sideBySide: true,
    ignoreReadonly: true
});

$('#tempsImparti').datetimepicker({
    format: 'LT',
    locale: 'fr',
    defaultDate: moment().hour(2).minute(0),
    ignoreReadonly: true
});

$('#penalite').datetimepicker({
    format: 'H:m:ss',
    locale: 'fr',
    defaultDate: moment().hour(0).minute(15).seconds(0),
    ignoreReadonly: true
});

// Ajouter une balise
$('.ajouter').click(function() {
  $('.champ-cache').first().clone().appendTo('.champ-visible').show();
});

// Supprimer une balise
$('.supprimer').click(function() {
  $(this).closest('.form-inline').remove();
});

// affiche le champ "Valeur" en mode score
$('#S').click(function() {
    $('.champ-cache :input[type="hidden"]').attr('type', 'number');
});

// cache le champ "Valeur" en mode parcours
$('#P').click(function() {
    $('.champ-cache :input[type="number"]').attr('type', 'hidden');
});

// si le champ parcours est pré-sélectionné on cache aussi "valeur"
if($('#P').is(':checked')) {
  $('.champ-cache :input[type="number"]').attr('type', 'hidden');
}
