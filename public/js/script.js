/*
 * Personalisation JavaScript / JQuery
 */

$(document).ready(function() {
    $('#table').DataTable();
} );

// Désactive l'appui sur la touche entrée
$('#map').bind('keypress', function(e)
{
  if(e.keyCode == 13) {
    return false;
  }
});

// Confirmer la suppression d'une course
$('#supprModal').on('show.bs.modal', function(e) {
  $(this).find('.btn-primary').attr('href', $(e.relatedTarget).data('href'));
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
  supprimerBalise();
});

// Supprimer une balise
function supprimerBalise() {
  $('.supprimer').off();
  $('.supprimer').click(function() {
    $(this).closest('.form-inline').remove();
  });
}
supprimerBalise();

// cache "penalite" en mode score
$('#S').click(function() {
    modeScore();
});

// cache "Valeur" et "tempsImparti" en mode parcours
$('#P').click(function() {
    modeParcours();
});

// si le champ parcours est pré-sélectionné
if($('#S').is(':checked')) {
  modeScore();
}
// si le champ parcours est pré-sélectionné
if($('#P').is(':checked')) {
  modeParcours();
}

function modeScore() {
  $('.champ-cache :input[type="hidden"]').attr('type', 'number');
  $('.tempsImparti').show();
  $('.penalite').hide();
}
function modeParcours() {
  $('.champ-cache :input[type="number"]').attr('type', 'hidden');
  $('.tempsImparti').hide();
  $('.penalite').show();
}
