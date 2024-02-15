jQuery(document).ready(function ($) {
  const autoCompleteModal = $('#pwr-autocomplete-modal')
  const destinationHotel = $('#pwr-destination-hotel')

  // Event handler for input focus
  destinationHotel.on('focus', function () {
    if ($(this).val().length > 0) {
      autoCompleteModal.removeClass('pwr-hidden')
    }
  })

  // Event handler for input on user typing
  destinationHotel.on('input', function() {
    const searchTerm = $(this).val().trim();
    const autocompleteModal = $('#pwr-autocomplete-modal');

    if (searchTerm.length === 0) {
      autocompleteModal.addClass('pwr-hidden');
      return;
    }

    $.ajax({
      url: pwrAutocomplete.ajaxurl,
      type: 'POST',
      data: {
        action: 'pwr_search_destination',
        term: searchTerm
      },
      success: function(response) {
        if (response.success && response.data.trim().length > 0) {
          // Append new results and show modal only if there's content
          autocompleteModal.html(response.data).removeClass('pwr-hidden');
        } else {
          // Hide the modal if no content
          autocompleteModal.addClass('pwr-hidden');
        }
      }
    });
  });

  // Event handler for clicking on an autocomplete item
  $(document).on('click', '.pwr-autocomplete-item', function() {
    const selectedItem = $(this);

    // Extract the selected value and IDs
    const selectedValue = selectedItem.text();
    const countryId = selectedItem.find('.pwr-country-id-section').val();
    const regionGroupId = selectedItem.find('.pwr-region-group-id-section').val();
    const regionId = selectedItem.find('.pwr-region-id-section').val();
    const cityId = selectedItem.find('.pwr-city-id-section').val();
    const accommodationId = selectedItem.find('.pwr-accommodation-id-section').val();

    $('#pwr-destination-hotel').val(selectedValue);
    $('#pwr-nid').val(countryId);
    $('#pwr-rgid').val(regionGroupId);
    $('#pwr-rid').val(regionId);
    $('#pwr-cyid').val(cityId);
    $('#pwr-aid').val(accommodationId);
    $('#pwr-autocomplete-modal').addClass('pwr-hidden');
  });

  $(document).on('click', function (e) {
    if (!$(e.target).closest('#pwr-destination-hotel').length &&
      !$(e.target).closest('#pwr-autocomplete-modal').length) {
      autoCompleteModal.addClass('pwr-hidden')
    }
  })
})
