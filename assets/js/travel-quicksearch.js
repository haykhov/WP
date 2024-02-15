/* Select 2 drop down*/
jQuery(document).ready(function ($) {
  $('#pwr-departure-airports').select2({
    placeholder: 'Abflughafen',
    allowClear: true,
    minimumResultsForSearch: 0,
    closeOnSelect: false,
    dropdownParent: $('#pwr-travel-search-form')
  });
});

/* Calendar date range picker */
jQuery(document).ready(function ($) {
  const dateRangePicker = $('#pwr-travel-dates');
  const clonedDropdown = $('.pwr-travel-duration').detach();
  const durationInput = $('#pwr-dur');

  // Define default start and end dates
  const defaultStartDate = moment().add(1, 'days');
  const defaultEndDate = moment().add(8, 'days');

  let selectedDurationText = '';

  dateRangePicker.daterangepicker({
    'autoApply': true,
    'locale': {
      'format': 'DD.MM.YYYY',
      'separator': ' - ',
      'applyLabel': 'ÜBERNEHMEN',
      'cancelLabel': 'Abbrechen',
      'fromLabel': 'Von',
      'toLabel': 'Bis',
      'customRangeLabel': 'Benutzerdefiniert',
      'weekLabel': 'W',
      'daysOfWeek': [
        'So',
        'Mo',
        'Di',
        'Mi',
        'Do',
        'Fr',
        'Sa',
      ],
      'monthNames': [
        'Januar',
        'Februar',
        'März',
        'April',
        'Mai',
        'Juni',
        'Juli',
        'August',
        'September',
        'Oktober',
        'November',
        'Dezember',
      ],
      'firstDay': 1,
    },
    'minDate': new Date(),
    'startDate': defaultStartDate,
    'endDate': defaultEndDate,

  }, function(start, end, label) {
    // This function gets called when the range is applied
    $('#pwr-ddate').val(start.format('YYYY-MM-DD'));
    $('#pwr-rdate').val(end.format('YYYY-MM-DD'));
    // Update the visible input with the selected dates and duration
    $('#pwr-travel-dates').val(start.format('DD.MM.YYYY') + ' - ' + end.format('DD.MM.YYYY') + ', ' + label);
  }).on('show.daterangepicker', function (ev, picker) {
    const $picker = $(picker.container)

    if (!$picker.find('.calendar-title').length) {
      $picker.prepend(
        $('<div class="pwr-label calendar-title">Reisezeitraum</div>'))
    }

    if (!$picker.find('.duration-title').length) {
      $picker.find('.drp-calendar.left').append(
        $('<div class="pwr-label duration-title">Reisedauer</div>'))
    }

    if (!$picker.find('.drp-calendar.left .select2-hidden-accessible').length) {
      clonedDropdown.appendTo($picker.find('.drp-calendar.left')).select2({
        dropdownParent: $picker.find('.drp-calendar.left'),
        width: '100%',
      }).addClass('full-width').show()
    }
  }).on('hide.daterangepicker', function (ev, picker) {
    const $picker = $(picker.container)

    $picker.find('.drp-calendar.left .select2-hidden-accessible').select2(
      'destroy')
    clonedDropdown.hide()

    const dateRangeText = picker.startDate.format(picker.locale.format) +
      ' - ' + picker.endDate.format(picker.locale.format)
    dateRangePicker.val(dateRangeText + '  ' + selectedDurationText)

  }).on('apply.daterangepicker', function (ev, picker) {
    const dateRangeText = picker.startDate.format(picker.locale.format) +
      ' - ' + picker.endDate.format(picker.locale.format)
    dateRangePicker.val(dateRangeText + '  ' + selectedDurationText)
  })

  clonedDropdown.on('select2:select', function (e) {
    selectedDurationText = e.params.data.text
    durationInput.val(e.params.data.id)
    dateRangePicker.data('daterangepicker').clickApply();
  })

  // Update hidden inputs with default values on page load
  $('#pwr-ddate').val(defaultStartDate.format('YYYY-MM-DD'));
  $('#pwr-rdate').val(defaultEndDate.format('YYYY-MM-DD'));
})

/* Travelers*/
jQuery(document).ready(function ($) {
  const travelers = $('#pwr-travelers');
  const travelersModal = $('#pwr-travelers-modal');

  const adultsInput = $('#pwr-adults');
  const childrenInput = $('#pwr-children');
  adultsInput.val('2');
  childrenInput.val('0');

  updateTravelersInput();

  travelers.on('click', function (e) {
    travelersModal.toggle();
    e.stopPropagation();
  });

  $(document).on('click', function (e) {
    if (!$(e.target).closest('#pwr-travelers, #pwr-travelers-modal').length) {
      travelersModal.hide();
    }
  });

  $('.pwr-increase, .pwr-decrease').on('click', function () {
    const input = $(this).siblings('input');
    const currentValue = parseInt(input.val(), 10);
    let newValue = currentValue;

    if ($(this).hasClass('pwr-increase')) {
      if (currentValue < 4) {
        newValue = currentValue + 1;
      }
    } else {
      newValue = Math.max(currentValue - 1, input.attr('min'));
    }

    input.val(newValue).change();

    updateChildrenAgesInput();
  });

  $('#pwr-adults, #pwr-children').on('change', function () {
    updateTravelersInput();
    if ($(this).attr('id') === 'pwr-children') {
      updateChildAgeDropdowns($(this).val());
    }
  });

  function updateTravelersInput() {
    const adults = adultsInput.val();
    const children = childrenInput.val();
    const childrenText = children === '1' ? 'Kind' : 'Kinder';
    travelers.val(adults + ' Erwachsene, ' + children + ' ' + childrenText);
  }

  function updateChildAgeDropdowns(numChildren) {
    const agesContainer = $('#pwr-children-ages')

    if(numChildren === '0') {
      agesContainer.empty();
      return;
    }

    const currentNumDropdowns = agesContainer.find(
      'select.pwr-child-age').length

    for (let i = currentNumDropdowns; i < numChildren; i++) {
      agesContainer.append(createChildAgeDropdown(i));
    }

    agesContainer.find('select.pwr-child-age:gt(' + (numChildren - 1) + ')').remove();
  }

  function createChildAgeDropdown(index) {
    if(index === 0) {
      const label = $('<label/>', {
        text: 'Alter der Kinder bei Rückreise',
        'class': 'pwr-label',
      })
      $('#pwr-children-ages').prepend(label);
    }

    const ageDropdown = $('<select></select>', {
      'class': 'pwr-child-age',
      'name': 'child-age-' + index,
    }).append('<option value="1">< 2 Jahre</option>')

    for (let age = 2; age <= 17; age++) {
      ageDropdown.append($('<option></option>', {
        'value': age,
        'text': age + ' Jahre',
      }));
    }

    return ageDropdown;
  }

  // Function to update the children's ages input value
  function updateChildrenAgesInput() {
    const ageValues = []
    $('#pwr-children-ages .pwr-child-age').each(function() {
      ageValues.push($(this).val());
    });

    $('#pwr-children-values').val(ageValues.join(','));
  }

  $(document).on('change', '.pwr-child-age', function() {
    updateChildrenAgesInput();
  });
});

jQuery(document).ready(function($) {
  $('#pwr-travel-search-submit').click(function(e) {
    e.preventDefault(); // Prevent the default form submission

    const baseUrl = 'https://www.reiselodern.de/buchen/pauschalreisen';
    let params = {
      destination: $('#pwr-destination-hotel').val(),
      nid: $('#pwr-nid').val(),
      rgid: $('#pwr-rgid').val(),
      rid: $('#pwr-rid').val(),
      cyid: $('#pwr-cyid').val(),
      aid: $('#pwr-aid').val(),
      ddate: $('#pwr-ddate').val(),
      rdate: $('#pwr-rdate').val(),
      dur: $('#pwr-dur').val(),
      adult: $('#pwr-adults').val(),
      child: ($('#pwr-children').val() > 0 ? $('#pwr-children-ages .pwr-child-age').map(function() { return $(this).val(); }).get().join(',') : ''),
      depap: $('#pwr-departure-airports').val() ? $('#pwr-departure-airports').val().join(',') : ''
    };

    // Custom function to encode URI components except commas
    function customEncodeURIComponent(str) {
      return encodeURIComponent(str).replace(/%2C/g, ',');
    }

    // Manually construct the query string
    const queryString = Object.keys(params)
    .map(key => `${customEncodeURIComponent(key)}=${customEncodeURIComponent(params[key])}`)
    .join('&');

    window.location.href = baseUrl + '?' + queryString;
  });
});
